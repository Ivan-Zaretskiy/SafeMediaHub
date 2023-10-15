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

    private static function generateLockPIN($len)
    {
        return str_repeat('*', $len);
    }
}
