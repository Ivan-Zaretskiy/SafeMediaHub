<?php
class keysManager{
    private $userId;
    private $key;
    private $cipher = "AES-128-CBC";
    private $keyUrl = "keys.env";
    private $options = OPENSSL_RAW_DATA;
    private $ivlen;
    private $sha2len = 32;
    private $bitesCount = 1024;

    public function __construct()
    {
        $this->userId = (int)$_SESSION['loginUser']['id'];
        $this->ivlen = openssl_cipher_iv_length($this->cipher);
        if (!file_exists($this->keyUrl)) {
            $this->generateKey();
        }
        $this->key = $this->readKey();
    }

    public function encryptString($text)
    {
        $iv = openssl_random_pseudo_bytes($this->ivlen);
        $ciphertext_raw = openssl_encrypt($text, $this->cipher, $this->key, $this->options, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $this->key, true);
        $ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );

        return $ciphertext ?? false;
    }

    public function decryptString($text)
    {
        $c = base64_decode($text);
        $iv = substr($c, 0, $this->ivlen);
        $hmac = substr($c, $this->ivlen, $this->sha2len);
        $ciphertext_raw = substr($c, $this->ivlen+$this->sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $this->cipher, $this->key, $this->options, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $this->key, true);

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
        $txt = openssl_random_pseudo_bytes($this->bitesCount);
        fwrite($myFile, $txt);
        fclose($myFile);

        return $txt;
    }

    public function readKey()
    {
        $myFile = fopen($this->keyUrl, "r") or die("Unable to open file!");
        return fgets($myFile);
    }

    public function removeKeyFile(): bool
    {
        return unlink($this->keyUrl);
    }

    public function addCustomField()
    {
        if (!isset($_GET['ajax'])) {
            $ajax = [];
            $fieldName = mres($_POST['fieldName']);
            $fieldValue = mres($_POST['fieldValue']);
            $userId = $this->userId;

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
        $q = 'SELECT * FROM `encryptedString` WHERE `user_id` = '.$this->userId;
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

    public function deleteField()
    {
        $ajax = [];
        $ajax['success'] = false;
        $id = (int)$_POST['id'];
        $q = 'DELETE FROM `encryptedString` WHERE `id` = '.$id;
        if (mq($q)) {
            $ajax['success'] = true;
        }
        echo json_encode($ajax);
    }

    public function editField()
    {
        $id = (int)$_GET['id'];
        if (!empty($id)) {
            if (!$_GET['ajax']) {
                $ajax = [];
                $ajax['success'] = false;
                $fieldName = mres($_POST['fieldName']);
                $fieldValue = mres($_POST['fieldValue']);
                $encryptedName = $this->encryptString($fieldName);
                $encryptedValue = $this->encryptString($fieldValue);

                $q = 'UPDATE `encryptedString` SET `encryptedText` = "'.$encryptedValue.'", `name` = "'.$encryptedName.'" WHERE `id` = '.$id;
                if (mq($q)){
                    $ajax['success'] = true;
                    $ajax['name'] = $fieldName;
                }
                echo json_encode($ajax);
            } else {
                $fieldInfo = getRowQuery('SELECT * FROM `encryptedString` WHERE `id` = ' . $id);
                $fieldName = $this->decryptString($fieldInfo['name']) ?? '';
                $fieldValue = $this->decryptString($fieldInfo['encryptedText']) ?? '';

                include_once('attaches/modalEditField.php');
                die();
            }
        }
    }
}
