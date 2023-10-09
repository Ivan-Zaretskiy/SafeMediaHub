<?php

class user_settings
{

    public function profile()
    {
        global $user;

        $firstPIN = self::generateLockPIN(4);
        $secondPIN = self::generateLockPIN(8);
        include_once('attaches/show_profile.php');
        die();
    }

    public function changePassword()
    {
        global $user;

        if (!isset($_GET['ajax'])) {
            $ajax = [];
            $ajax['success'] = false;
            $currentPassword = $_POST['currentPassword'];
            $newPassword = $_POST['newPassword'];
            $confirmPassword = $_POST['confirmPassword'];
            $pass_verify = password_verify($currentPassword, $user->password);
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
                        ":id" => $user->getUserID(),
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
            die();
        }
    }

    public function editProfile()
    {
        global $user;

        $ajax = [];
        $ajax['success'] = false;
        $email = $_POST['email'];
        $username = $_POST['username'];

        if ($_POST['firstPIN'] == '****') {
            $firstPIN = $user->firstPIN;
        } else {
            if (!is_numeric($_POST['firstPIN']) && strlen($_POST['firstPIN']) !== 4){
                $ajax['text'] = 'Invalid first PIN';
                echo json_encode($ajax);
                die();
            }
            $firstPIN = password_hash($_POST['firstPIN'],PASSWORD_BCRYPT, ['cost' => 12]);
        }

        if ($_POST['secondPIN'] == '********') {
            $secondPIN = $user->secondPIN;
        } else {
            if (!is_numeric($_POST['secondPIN']) && strlen($_POST['secondPIN']) !== 8){
                $ajax['text'] = 'Invalid second PIN';
                echo json_encode($ajax);
                die();
            }
            $secondPIN = password_hash($_POST['secondPIN'], PASSWORD_BCRYPT, ['cost' => 12]);
        }

        if (empty($username)) {
            $ajax['text'] = 'Invalid username';
            echo json_encode($ajax);
            die();
        }
        if (empty($email)) {
            $ajax['text'] = 'Invalid email';
            echo json_encode($ajax);
            die();
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
            ':id' => $user->getUserID(),
            ':email' => $email,
            ':username' => $username,
            ':firstPIN' => $firstPIN,
            ':secondPIN' => $secondPIN,
        ])->execute();
        $ajax['success'] = true;
        echo json_encode($ajax);
        die();
    }

    private static function generateLockPIN($len)
    {
        return str_repeat('*', $len);
    }
}
