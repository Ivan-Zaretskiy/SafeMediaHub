<?php
class notes {

    public static function insertInDB($encryptedName, $encryptedValue) {
        return query("
        INSERT INTO
            notes
        SET
            user_id = :user_id,
            name = :name,
            value = :encryptedValue
        ", [
            ":user_id" => SessionUser::getUserID(),
            ":name" => $encryptedName,
            ":encryptedValue" => $encryptedValue
        ])->execute();
    }

    public static function getEncryptedValueById($id) {
        return query('SELECT value FROM notes WHERE id = ?', $id)->fetchCell();
    }

    public function addCustomField() {

        if (!isset($_GET['ajax'])) {
            $ajax = [];
            $fieldName = $_POST['fieldName'];
            $fieldValue = $_POST['fieldValue'];

            $ajax['success'] = false;
            if (!empty($fieldName) && !empty($fieldValue)) {
                $encryptedName = KeyHelper::encryptString($fieldName);
                $encryptedValue = KeyHelper::encryptString($fieldValue);
                $result = self::insertInDB($encryptedName, $encryptedValue);
                if ($result) {
                    $ajax['success'] = true;
                    $ajax['name'] = $fieldName;
                }
            }
            echo json_encode($ajax);
        } else {
            include_once('attaches/modalAddKeysField.php');
        }
    }

    public function getFields() {
        $result = query("SELECT * FROM notes WHERE user_id = ?", SessionUser::getUserID())->fetchAll();
        foreach ($result as $item) {
            $item->name = KeyHelper::decryptString($item->name);
        }

        echo json_encode(['data'=>$result]);
    }

    public function loadDecryptedField() {
        $ajax = [];
        $ajax['success'] = false;
        $id = (int)$_POST['id'];
        $encrypted = self::getEncryptedValueById($id);
        if (!empty($encrypted)) {
            $decrypted = KeyHelper::decryptString($encrypted);
            if (!empty($decrypted)) {
                $ajax['success'] = true;
                $ajax['field'] = $decrypted;
            }
        }
        echo json_encode($ajax);
    }

    public function editField() {
        if (!$_GET['ajax']) {
            $id = (int)$_GET['id'];
            $ajax = [];
            $fieldName = $_POST['fieldName'];
            $fieldValue = $_POST['fieldValue'];
            $encryptedName = KeyHelper::encryptString($fieldName);
            $encryptedValue = KeyHelper::encryptString($fieldValue);
            query('
            UPDATE
                notes
            SET
                encryptedValue = :encryptedValue,
                name = :name
            WHERE
                id = :id
            ', [
                ":id" => $id,
                ":name" => $encryptedName,
                ":encryptedValue" => $encryptedValue,
            ])->execute();
            $ajax['success'] = true;
            $ajax['name'] = $fieldName;
            echo json_encode($ajax);
        } else {
            include_once('attaches/modalEditField.php');
        }
    }

    public function checkPin() {
        if (!isset($_GET['ajax'])) {
            $result = password_verify($_POST['PIN'], SessionUser::get('firstPIN'));;
            $ajax['success'] = $result;
            if ($ajax['success']) {
                switch ($_POST['data']['func']) {
                    case 'deleteImage':
                        $id = (int) $_POST['data']['id'];
                        query('DELETE FROM images WHERE user_id = :user_id AND id = :id', [":user_id" => SessionUser::getUserID(), ":id" => $id]);
                        break;
                    case 'showNotes':
                        $id = (int) $_POST['data']['id'];
                        $ajax['decrypted_value'] = KeyHelper::decryptString(self::getEncryptedValueById($id));
                        break;
                    case 'deleteField':
                        $id = (int) $_POST['data']['id'];
                        query('DELETE FROM notes WHERE user_id = :user_id AND id = :id', [":user_id" => SessionUser::getUserID(), ":id" => $id]);
                        break;
                    case 'deleteSeries':
                        $id = (int) $_POST['data']['id'];
                        query('DELETE FROM series WHERE user_id = :user_id AND id = :id', [":user_id" => SessionUser::getUserID(), ":id" => $id]);
                        break;
                }
            }
            echo json_encode($ajax);
        } else {
            include_once('attaches/checkPin.php');
        }
    }

    public function check2Pins() {
        if (!isset($_GET['ajax'])) {
            $result = password_verify($_POST['PIN'], SessionUser::get('firstPIN'));
            $result2 = password_verify($_POST['PIN2'], SessionUser::get('secondPIN'));
            $ajax['success'] = $result && $result2;
            if ($ajax['success']) {
                switch ($_POST['data']['func']) {
                    case 'openImage':
                    case 'openImageNewWindow':
                        $id = (int)$_POST['data']['id'];
                        $image = query('SELECT file FROM images WHERE user_id = :user_id AND id = :id', [":user_id" => SessionUser::getUserID(), ":id" => $id])->fetchCell();
                        $ajax['file'] = base64_encode(KeyHelper::decryptString($image));
                        break;
                    case 'editField':
                        $id = (int)$_POST['data']['id'];
                        $fieldInfo = query('SELECT * FROM notes WHERE user_id = :user_id AND id = :id', [":user_id" => SessionUser::getUserID(), ":id" => $id])->fetchRow();
                        $ajax['field']['id'] = $id;
                        $ajax['field']['name'] = KeyHelper::decryptString($fieldInfo->name) ?? '';
                        $ajax['field']['value'] = KeyHelper::decryptString($fieldInfo->value) ?? '';
                        break;
                    case 'downloadImage':
                        $id = (int)$_POST['data']['id'];
                        $image = query('SELECT * FROM images WHERE user_id = :user_id AND id = :id', [":user_id" => SessionUser::getUserID(), ":id" => $id])->fetchRow();
                        $base64strImg = base64_encode(KeyHelper::decryptString($image->file));
                        $ajax['img'] = "data:image/jpg;base64, ". $base64strImg;
                        $ajax['name'] = $image->name;
                        break;
                    case 'generateMyKey':
                        $old_key = KeyHelper::getKey();
                        if (!$old_key) {
                            $ajax['error_message'] = 'You need upload your current key before update!';
                            break;
                        }
                        $new_key = KeyHelper::generateKeyString();
                        $name_file = SessionUser::get('username') . '.env';
                        $ajax['name'] = $name_file;
                        $this->saveKeyFile($name_file, $new_key);
                        $key_hash = password_hash($new_key, PASSWORD_BCRYPT, ['cost' => 12]);
                        query('
                        UPDATE
                            users
                        SET
                            have_key = 1,
                            key_created_at = CURRENT_TIMESTAMP(),
                            key_hash = :key_hash
                        WHERE
                            id = :id
                        ', [
                            ":id" => SessionUser::getUserID(),
                            ":key_hash" => $key_hash
                        ])->execute();
                        SessionUser::setUserKey($new_key);
                        SessionUser::updateUserData();
                        $this->updateAllValuesByNewKey($old_key, $new_key);
                        KeyHelper::setKey(KeyHelper::getKey());
                        break;
                    case 'resetKey':
                        query('UPDATE users SET have_key = 0, key_created_at = NULL, key_hash = NULL WHERE id = ?', SessionUser::getUserID())->execute();
                        SessionUser::updateUserData();
                        $this->deleteAllUserEncryptedValues();
                        $ajax['success'] = true;
                        break;
                }
            }
            echo json_encode($ajax);
        } else {
            include_once('attaches/check2Pins.php');
        }
    }

    private function updateAllValuesByNewKey($old_key, $new_key) {
        $notes = query('SELECT * FROM notes WHERE user_id = ?', SessionUser::getUserID())->fetchAll();
        foreach ($notes as $note) {
            $oldName = KeyHelper::decryptString($note->name, $old_key);
            $oldEncryptedValue = KeyHelper::decryptString($note->value, $old_key);
            $newName = KeyHelper::encryptString($oldName, $new_key);
            $newEncryptedValue = KeyHelper::encryptString($oldEncryptedValue, $new_key);
            query('
            UPDATE
                notes
            SET
                value = :encryptedValue,
                name = :name
            WHERE
                id = :id
            ', [
                ":encryptedValue" => $newEncryptedValue,
                ":name" => $newName,
                ":id" => $note->id
            ])->execute();
        }
        $images = query('SELECT * FROM images WHERE user_id = ?', SessionUser::getUserID())->fetchAll();
        foreach ($images as $image) {
            $oldFile = KeyHelper::decryptString($image->file, $old_key);
            $newFile = KeyHelper::encryptString($oldFile, $new_key);
            query('
            UPDATE
                images
            SET
                file = :file
            WHERE
                id = :id
            ', [
                ":file" => $newFile,
                ":id" => $image->id
            ]);
        }

    }

    public function uploadKey() {
        if (!isset($_GET['ajax'])) {
            $ajax = [];
            $ajax['success'] = false;
            $key = file_get_contents($_FILES['key']['tmp_name']);
            if (password_verify($key, SessionUser::get('key_hash'))) {
                $ajax['success'] = true;
                SessionUser::setUserKey($key);
            } else {
                $ajax['error_message'] = 'Wrong key';
            }
            echo json_encode($ajax);
        } else {
            include_once('attaches/modalUploadKey.php');
        }
    }

    public function saveKeyFile($name_file, $new_key) {
        $path = 'temporary_user_files/';
        mkdir($path, 0755, true);
        $txt = fopen($path.$name_file, "w+") or doError("Unable to open file!");
        fwrite($txt, $new_key);
        fclose($txt);
    }

    public function removeEnvFile() {
        $path = 'temporary_user_files/';
        $name = $_POST['name'];
        unlink($path.$name);
    }

    public function deleteAllUserEncryptedValues() {
        query("DELETE FROM images WHERE user_id = ?", SessionUser::getUserID())->execute();
        query("DELETE FROM notes WHERE user_id = ?", SessionUser::getUserID())->execute();
    }
}
