<?php

class settings
{

    public function profile()
    {
        $firstPIN = self::generateLockPIN(4);
        $secondPIN = self::generateLockPIN(8);

        include_once('attaches/show_profile.php');
    }

    public function changePassword()
    {
        if (!isset($_GET['ajax'])) {
            $ajax = [];
            $ajax['success'] = false;
            $currentPassword = $_POST['currentPassword'];
            $newPassword = $_POST['newPassword'];
            $confirmPassword = $_POST['confirmPassword'];
            $pass_verify = password_verify($currentPassword, SessionUser::get('password'));
            if ($newPassword == $confirmPassword) {
                if ($pass_verify) {
                    $hash_password = password_hash($newPassword,PASSWORD_BCRYPT,['cost' => 12]);
                    query("
                    UPDATE
                        users
                    SET
                        password = :password
                    WHERE
                        id = :id
                    ", [
                        ":id" => SessionUser::getUserID(),
                        ":password" => $hash_password
                    ])->execute();
                    $ajax['success'] = true;
                } else {
                    $ajax['text'] = 'Wrong current password';
                }
            } else {
                $ajax['text'] = 'New passwords doesn\'t match';
            }
            echo json_encode($ajax);
        } else {
            include_once('attaches/modalChangePassword.php');
        }
    }

    public function editProfile()
    {
        $ajax = [];
        $ajax['success'] = false;
        $email = $_POST['email'];
        $username = $_POST['username'];

        if ($_POST['firstPIN'] == '****') {
            $firstPIN = SessionUser::get('firstPIN');
        } else {
            if (!is_numeric($_POST['firstPIN']) && strlen($_POST['firstPIN']) !== 4){
                $ajax['text'] = 'Invalid first PIN';
                echo json_encode($ajax);
                ApplicationHelper::exit();
            }
            $firstPIN = password_hash($_POST['firstPIN'],PASSWORD_BCRYPT, ['cost' => 12]);
        }

        if ($_POST['secondPIN'] == '********') {
            $secondPIN = SessionUser::get('secondPIN');
        } else {
            if (!is_numeric($_POST['secondPIN']) && strlen($_POST['secondPIN']) !== 8){
                $ajax['text'] = 'Invalid second PIN';
                echo json_encode($ajax);
                ApplicationHelper::exit();
            }
            $secondPIN = password_hash($_POST['secondPIN'], PASSWORD_BCRYPT, ['cost' => 12]);
        }

        if (empty($username)) {
            $ajax['text'] = 'Invalid username';
            echo json_encode($ajax);
            ApplicationHelper::exit();
        }
        if (empty($email)) {
            $ajax['text'] = 'Invalid email';
            echo json_encode($ajax);
            ApplicationHelper::exit();
        }

        query('
        UPDATE
            users
        SET
            email = :email,
            username = :username,
            firstPIN = :firstPIN,
            secondPIN = :secondPIN
        WHERE
            id = :id
        ', [
            ':id' => SessionUser::getUserID(),
            ':email' => $email,
            ':username' => $username,
            ':firstPIN' => $firstPIN,
            ':secondPIN' => $secondPIN,
        ])->execute();
        $ajax['success'] = true;
        echo json_encode($ajax);
        ApplicationHelper::exit();
    }

    public function switchMode() {
        $mode = !SessionUser::isDarkMode();
        query("
        UPDATE
            users
        SET
            dark_mode = :mode
        WHERE
            id = :id
        ", [
            ':mode' => $mode,
            ':id' => SessionUser::getUserID()
        ])->execute();
        redirect('/');
    }

    private static function generateLockPIN($len)
    {
        return str_repeat('*', $len);
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
