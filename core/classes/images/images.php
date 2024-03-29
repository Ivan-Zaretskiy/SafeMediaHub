<?php
class images {

    public function main() {
        include_once('attaches/main.php');
    }

    public function getImages() {
        $result = query("SELECT * FROM images WHERE user_id = ?", SessionUser::getUserID())->fetchAll();

        echo json_encode(['data'=>$result]);
    }

    public function loadNewImage() {
        if (!isset($_GET['ajax'])) {
            $name = !empty($_POST['name']) ? $_POST['name'] : date('d-m-Y_H:i:s');
            $name = str_replace(' ', '_', trim($name));
            $name = $name . '.jpg';
            $image = file_get_contents($_POST['url_image']);
            $encrypt = KeyHelper::encryptString($image);
            query("
            INSERT INTO
                images
            SET
                user_id = :user_id,
                name = :image_name,
                file = :file
            ", [
                ':user_id' => SessionUser::getUserID(),
                ':image_name' => $name,
                ':file' => $encrypt
            ])->execute();
            $ajax['success'] = true;
            echo json_encode($ajax);
        } else {
            include_once('attaches/modalNewImage.php');
        }
    }

    public function loadNewImageFile() {
        if (!isset($_GET['ajax'])) {
            $new_name = !empty($_POST['name']) ? $_POST['name'] : date('d-m-Y_H:i:s');
            $new_name = str_replace(' ', '_', trim($new_name));
            $new_name = $new_name .'.jpg';
            $name = md5(mt_rand(100, 200)).time().'.jpg';
            $target_dir = "img/";
            if (move_uploaded_file($_FILES['file']['tmp_name'], $target_dir . $name)) {
                $image_base64 = KeyHelper::encryptString(file_get_contents($target_dir.$name));
                unlink($target_dir . $name);
                query("
                INSERT INTO
                    images
                SET
                    user_id = :user_id,
                    name = :new_name,
                    file = :file
                ", [
                    ':user_id' => SessionUser::getUserID(),
                    ':new_name' => $new_name,
                    ':file' => $image_base64
                ])->execute();
                $ajax['success'] = true;
            } else {
                $ajax['success'] = false;
                $ajax['error_message'] = 'Error on moving';
            }
            echo json_encode($ajax);
        } else {
            include_once('attaches/modalNewImageFile.php');
        }
    }

    public function openImage() {
        include_once('attaches/simpleOpenImage.php');
    }

    public function deleteImage() {
        $ajax['success'] = false;
        $id = (int) $_POST['id'];
        if ($id) {
            query('
            DELETE FROM
                images
            WHERE
                user_id = :user_id
                AND id = :id
            ', [
                ':id' => $id,
                ':user_id' => SessionUser::getUserID()
            ])->execute();
            $ajax['success'] = true;
        }
        echo json_encode($ajax);
    }
}
