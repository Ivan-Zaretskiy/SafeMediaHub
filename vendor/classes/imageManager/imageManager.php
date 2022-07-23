<?php
class imageManager
{
    public mixed $user;

    public function __construct()
    {
        $this->user = $_SESSION['loginUser'];
    }

    public function loadAllImages()
    {
        include_once('attaches/all_images.php');
        die();
    }

    public function getImages()
    {
        $q = 'SELECT * FROM `images` WHERE `user_id` = '.$this->user['id'];
        $result = getArrayQuery($q);

        echo json_encode(['data'=>$result]);
    }

    public function loadNewImage()
    {
        global $keyManager;
        if (!isset($_GET['ajax'])) {
            $name = mres($_POST['name']);
            $image = file_get_contents($_POST['url_image']);
            $encrypt = $keyManager->encryptString($image);
            $q = "INSERT INTO `images` SET `user_id` = ".$this->user['id'].", `name` = '".mres($name)."', `file` = '".$encrypt."'";
            if (mq($q)) {
                $ajax['success'] = true;
            } else {
                $ajax['success'] = false;
                $ajax['error_message'] = getSqliError();
            }
            echo json_encode($ajax);
        } else {
            include_once('attaches/modalNewImage.php');
        }
    }

    public function loadNewImageFile()
    {
        global $keyManager;
        if (!isset($_GET['ajax'])) {
            $new_name = $_POST['name'] ?? '';
            $new_name = $new_name .'.jpg';
            $name = md5(mt_rand(100, 200)).time().'.jpg';
            $target_dir = "img/";
            if (move_uploaded_file($_FILES['file']['tmp_name'], $target_dir . $name)) {
                $image_base64 = $keyManager->encryptString(file_get_contents($target_dir.$name));
                unlink($target_dir . $name);
                $q = "INSERT INTO `images` SET `user_id` = ".$this->user['id'].", `name` = '".mres($new_name)."', `file` = '".$image_base64."'";
                if (mq($q)) {
                    $ajax['success'] = true;
                } else {
                    $ajax['success'] = false;
                    $ajax['error_message'] = getSqliError();
                }
            } else {
                $ajax['success'] = false;
                $ajax['error_message'] = 'Error on moving';
            }
            echo json_encode($ajax);
        } else {
            include_once('attaches/modalNewImageFile.php');
        }
    }

    public function openImage()
    {
        global $keyManager;
        $id = (int)$_GET['id'];
        $q = 'SELECT * FROM `images` WHERE `user_id` = '.$this->user['id'].' AND `id` = '.$id;
        $image = getRowQuery($q);
        $image['decrypt'] = $keyManager->decryptString($image['file']);
        include_once('attaches/simpleOpenImage.php');
        die();
    }

    public function deleteImage()
    {
        $ajax = [];
        $id = (int)$_POST['id'];
        $q = 'DELETE FROM `images` WHERE `user_id` = '.$this->user['id'].' AND `id` = '.$id;
        if (mq($q)) {
            $ajax['success'] = true;
        } else {
            $ajax['success'] = false;
            $ajax['error_message'] = getSqliError();
            $ajax['q'] = $q;
        }
        echo json_encode($ajax);
    }

    public function getDecryptedHref()
    {
        global $keyManager;

        $id = (int)$_POST['id'];
        $q = 'SELECT * FROM `images` WHERE `user_id` = '.$this->user['id'].' AND `id` = '.$id;
        $image = getRowQuery($q);
        $ajax['success'] = true;
        $ajax['decrypt'] = base64_encode($keyManager->decryptString($image['file']));

        echo json_encode($ajax);
        die();
    }
}
