<?php
class keysManager{
    public $key;
    private $cipher = "AES-128-CBC";
    private $keyUrl = "keys.env";
    private $options = OPENSSL_RAW_DATA;
    private $ivlen;
    private $sha2len = 32;
    private $bitesCount = 1024;

    public function __construct()
    {
        $this->ivlen = openssl_cipher_iv_length($this->cipher);
        if (!file_exists($this->keyUrl)) $this->generateKey();
        $this->key = $this->getKey();
    }

    public function encryptString($text, $customKey = null)
    {
        $key = $customKey ?? $this->getKey();
        if (!$key) return false;
        $iv = openssl_random_pseudo_bytes($this->ivlen);
        $ciphertext_raw = openssl_encrypt($text, $this->cipher, $key, $this->options, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $key, true);
        $ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );

        return $ciphertext ?? false;
    }

    public function decryptString($text, $customKey = null)
    {
        $key = $customKey ?? $this->getKey();
        if (!$key) return false;
        $c = base64_decode($text);
        $iv = substr($c, 0, $this->ivlen);
        $hmac = substr($c, $this->ivlen, $this->sha2len);
        $ciphertext_raw = substr($c, $this->ivlen+$this->sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $this->cipher, $key, $this->options, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, true);

        return hash_equals($hmac, $calcmac) ? $original_plaintext : false;
    }

    public static function insertInDB($encryptedName, $encryptedText, $userId)
    {
        $q = "INSERT INTO `encryptedString` SET `user_id` = ".(int)$userId.", `name` = '".$encryptedName."', `encryptedText` = '".$encryptedText."'";
        return mq($q);
    }

    public static function getEncryptedValueByName($name)
    {
        return getValueQuery('SELECT `encryptedText` FROM `encryptedString` WHERE `name` = "'.$name.'"');
    }

    public static function getEncryptedValueById($id)
    {
        return getValueQuery('SELECT `encryptedText` FROM `encryptedString` WHERE `id` = '.$id);
    }

    public function generateKey()
    {
        $myFile = fopen($this->keyUrl, "w") or die("Unable to open file!");
        $txt = $this->generateKeyString();
        fwrite($myFile, $txt);
        fclose($myFile);

        return $txt;
    }

    public function generateKeyString()
    {
        return openssl_random_pseudo_bytes($this->bitesCount);
    }

    public function readKey()
    {
        $myFile = fopen($this->keyUrl, "r") or die("Unable to open file!");
        return fgets($myFile);
    }

    public function getKey()
    {
        global $user;
        return $user->have_key == 1 ? $user->key : $this->readKey();
    }

    public function removeKeyFile(): bool
    {
        return unlink($this->keyUrl);
    }

    public function addCustomField()
    {
        global $user;

        if (!isset($_GET['ajax'])) {
            $ajax = [];
            $fieldName = mres($_POST['fieldName']);
            $fieldValue = mres($_POST['fieldValue']);
            $userId = $user->getUserID();

            $ajax['success'] = false;
            if (!empty($fieldName) && !empty($fieldValue)) {
                $encryptedName = $this->encryptString($fieldName);
                $encryptedValue = $this->encryptString($fieldValue);
                $result = self::insertInDB($encryptedName, $encryptedValue, $userId);
                if ($result) {
                    $ajax['success'] = true;
                    $ajax['name'] = $fieldName;
                }
            }
            echo json_encode($ajax);
        } else {
            include_once('attaches/modalAddKeysField.php');
            die();
        }
    }

    public function getFields()
    {
        global $user;

        $q = 'SELECT * FROM `encryptedString` WHERE `user_id` = '.$user->getUserID();
        $result = getArrayQuery($q);
        for ($i = 0; $i < sizeof($result); $i++){
            $result[$i]['name'] = $this->decryptString($result[$i]['name']);
        }

        echo json_encode(['data'=>$result]);
    }

    public function loadDecryptedField()
    {
        $ajax = [];
        $ajax['success'] = false;
        $id = (int)$_POST['id'];
        $encrypted = self::getEncryptedValueById($id);
        if (!empty($encrypted)) {
            $decrypted = $this->decryptString($encrypted);
            if (!empty($decrypted)) {
                $ajax['success'] = true;
                $ajax['field'] = $decrypted;
            }
        }
        echo json_encode($ajax);
    }

    public function editField()
    {
        if (!$_GET['ajax']) {
            $id = (int)$_GET['id'];
            $ajax = [];
            $ajax['success'] = false;
            $fieldName = mres($_POST['fieldName']);
            $fieldValue = mres($_POST['fieldValue']);
            $encryptedName = $this->encryptString($fieldName);
            $encryptedValue = $this->encryptString($fieldValue);

            $q = 'UPDATE `encryptedString` SET `encryptedText` = "' . $encryptedValue . '", `name` = "' . $encryptedName . '" WHERE `id` = ' . $id;
            if (mq($q)) {
                $ajax['success'] = true;
                $ajax['name'] = $fieldName;
            }
            echo json_encode($ajax);
        } else {
            include_once('attaches/modalEditField.php');
            die();
        }
    }

    public function checkPin()
    {
        global $user;

        if (!isset($_GET['ajax'])) {
            $result = password_verify($_POST['PIN'], $user->firstPIN);;
            $ajax['success'] = $result;
            if ($ajax['success']) {
                switch ($_POST['data']['func']) {
                    case 'deleteImage':
                        $id = (int)$_POST['data']['id'];
                        $q = mq('DELETE FROM `images` WHERE `user_id` = '.$user->getUserID().' AND `id` = '.$id);
                        if (!$q) $ajax['error_message'] = getSqliError();
                        break;
                    case 'showEncryptedString':
                        $id = (int)$_POST['data']['id'];
                        $ajax['decrypted_value'] = $this->decryptString(self::getEncryptedValueById($id));
                        break;
                    case 'deleteField':
                        $id = (int)$_POST['data']['id'];
                        $q = mq('DELETE FROM `encryptedString` WHERE `user_id` = '.$user->getUserID().' AND `id` = '.$id);
                        if (!$q) $ajax['error_message'] = getSqliError();
                        break;
                    case 'deleteSerial':
                        $id = (int)$_POST['data']['id'];
                        $q = mq('DELETE FROM `serials` WHERE `user_id` = '.$user->getUserID().' AND `id` = '.$id);
                        if (!$q) $ajax['error_message'] = getSqliError();
                        break;
                }
            }
            echo json_encode($ajax);
            die();
        } else {
            include_once('attaches/checkPin.php');
            die();
        }
    }

    public function check2Pins()
    {
        global $user;

        if (!isset($_GET['ajax'])) {
            $result = password_verify($_POST['PIN'], $user->firstPIN);
            $result2 = password_verify($_POST['PIN2'], $user->secondPIN);
            $ajax['success'] = $result && $result2;
            if ($ajax['success']) {
                switch ($_POST['data']['func']) {
                    case 'openImage':
                    case 'openImageNewWindow':
                        $id = (int)$_POST['data']['id'];
                        $image = getValueQuery('SELECT `file` FROM `images` WHERE `user_id` = '.$user->getUserID().' AND `id` = '.$id);
                        $ajax['file'] = base64_encode($this->decryptString($image));
                        break;
                    case 'editField':
                        $id = (int)$_POST['data']['id'];
                        $fieldInfo = getRowQuery('SELECT * FROM `encryptedString` WHERE `user_id` = '.$user->getUserID().' AND `id` = ' . $id);
                        $ajax['field']['id'] = $id;
                        $ajax['field']['name'] = $this->decryptString($fieldInfo['name']) ?? '';
                        $ajax['field']['value'] = $this->decryptString($fieldInfo['encryptedText']) ?? '';
                        break;
                    case 'downloadImage':
                        $id = (int)$_POST['data']['id'];
                        $image = getRowQuery('SELECT * FROM `images` WHERE `user_id` = '.$user->getUserID().' AND `id` = '.$id);
                        $base64strImg = base64_encode($this->decryptString($image['file']));
                        $ajax['img'] = "data:image/jpg;base64, ". $base64strImg;
                        $ajax['name'] = $image['name'];
                        break;
                    case 'generateMyKey':
                        $old_key = $this->getKey();
                        if (!$old_key) {
                            $ajax['error_message'] = 'You need upload your current key before update!';
                            break;
                        }
                        $new_key = $this->generateKeyString();
                        $name_file = $user->username . '.env';
                        $ajax['name'] = $name_file;
                        $this->saveKeyFile($name_file, $new_key);
                        $key_hash = password_hash($new_key, PASSWORD_BCRYPT, ['cost' => 12]);
                        mq('UPDATE `users` SET `have_key` = 1, `key_created_at` = CURRENT_TIMESTAMP(), `key_hash` = "'.$key_hash.'" WHERE `id` = '.$user->getUserID());
                        $user->setUserKey($new_key);
                        $user->updateUserData();
                        $this->updateAllValuesByNewKey($old_key, $new_key);
                        $this->key = $this->getKey();
                        break;
                    case 'resetKey':
                        mq('UPDATE `users` SET `have_key` = 0, `key_created_at` = NULL, `key_hash` = NULL WHERE `id` = '.$user->getUserID());
                        $user->updateUserData();
                        $this->deleteAllUserEncryptedValues();
                        $ajax['success'] = true;
                }
            }
            echo json_encode($ajax);
            die();
        } else {
            include_once('attaches/check2Pins.php');
            die();
        }
    }

    private function updateAllValuesByNewKey($old_key, $new_key)
    {
        global $user;

        $encryptedStrings = getArrayQuery('SELECT * FROM `encryptedString` WHERE `user_id` = '.$user->getUserID());
        foreach ($encryptedStrings as $encryptedString) {
            $oldName = $this->decryptString($encryptedString['name'], $old_key);
            $oldEncryptedText = $this->decryptString($encryptedString['encryptedText'], $old_key);
            $newName = $this->encryptString($oldName, $new_key);
            $newEncryptedText = $this->encryptString($oldEncryptedText, $new_key);
            mq('UPDATE `encryptedString` SET `encryptedText` = "' . $newEncryptedText . '", `name` = "' . $newName . '" WHERE `id` = ' . $encryptedString['id']);
        }
        $images = getArrayQuery('SELECT * FROM `images` WHERE `user_id` = '.$user->getUserID());
        foreach ($images as $image) {
            $oldFile = $this->decryptString($image['file'], $old_key);
            $newFile = $this->encryptString($oldFile, $new_key);
            mq('UPDATE `images` SET `file` = "' . $newFile . '" WHERE `id` = ' . $image['id']);
        }

    }

    public function uploadKey()
    {
        global $user;
        if (!isset($_GET['ajax'])) {
            $ajax = [];
            $ajax['success'] = false;
            $key = file_get_contents($_FILES['key']['tmp_name']);
            if (password_verify($key, $user->key_hash)) {
                $ajax['success'] = true;
                $_SESSION['user_key'] = $key;
            } else {
                $ajax['error_message'] = 'Wrong key';
            }
            echo json_encode($ajax);
        } else {
            include_once('attaches/modalUploadKey.php');
        }
        die();
    }

    public function saveKeyFile($name_file, $new_key)
    {
        $path = 'temporary_user_files/';
        mkdir($path, 0755, true);
        $txt = fopen($path.$name_file, "w+") or die("Unable to open file!");
        fwrite($txt, $new_key);
        fclose($txt);
    }

    public function removeEnvFile()
    {
        $path = 'temporary_user_files/';
        $name = $_POST['name'];
        unlink($path.$name);
    }

    public function deleteAllUserEncryptedValues()
    {
        global $user;

        mq("DELETE FROM `images` WHERE `user_id` = ".$user->getUserID());
        mq("DELETE FROM `encryptedString` WHERE `user_id` = ".$user->getUserID());
    }
}
