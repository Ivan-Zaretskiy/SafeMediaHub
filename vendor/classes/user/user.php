<?php

class user
{
    public $user;

    public function __construct()
    {
        $this->user = $_SESSION['loginUser'];
    }

    public function profile()
    {
        $firstPIN = self::generateLockPIN(4);
        $secondPIN = self::generateLockPIN(8);
        include_once('attaches/show_profile.php');
        die();
    }

    public function changePassword()
    {

        if (!isset($_GET['ajax'])) {
            $ajax = [];
            $ajax['success'] = false;
            $currentPassword = mres($_POST['currentPassword']);
            $newPassword = mres($_POST['newPassword']);
            $confirmPassword = mres($_POST['confirmPassword']);
            $pass_verify = password_verify($currentPassword, $this->user['password']);
            if ($newPassword == $confirmPassword) {
                if ($pass_verify) {
                    $hash_password = password_hash($newPassword,PASSWORD_BCRYPT,['cost' => 12]);
                    $q = "UPDATE `users` SET `password` = '".$hash_password."' WHERE `id` = ".$this->user['id'];
                    if (mq($q)) {
                        $ajax['success'] = true;
                    } else {
                        $ajax['text'] = 'Can\'t update your password now. Try later!';
                    }
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
        global $keyManager;

        $ajax = [];
        $ajax['success'] = false;
        $email = mres($_POST['email']);
        $username = mres($_POST['username']);

        if ($_POST['firstPIN'] == '****') {
            $firstPIN = $this->user['firstPIN'];
        } else {
            if (!is_numeric($_POST['firstPIN']) && strlen($_POST['firstPIN']) !== 4){
                $ajax['text'] = 'Invalid first PIN';
                echo json_encode($ajax);
                die();
            }
            $firstPIN = password_hash($_POST['firstPIN'],PASSWORD_BCRYPT, ['cost' => 12]);
        }

        if ($_POST['secondPIN'] == '********') {
            $secondPIN = $this->user['secondPIN'];
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

        $q = 'UPDATE `users` SET `email` = "'.$email.'",
                                `username` = "'.$username.'",
                                `firstPIN` = "'.mres($firstPIN).'",
                                `secondPIN` = "'.mres($secondPIN).'"';

        if (mq($q)) {
            $ajax['success'] = true;
        } else {
            $ajax['text'] = 'Error on update profile. Try again!';
            $ajax['q'] = $q;
            $ajax['error_message'] = getSqliError();
        }
        echo json_encode($ajax);
        die();
    }

    private static function generateLockPIN($len)
    {
        return str_repeat('*', $len);
    }
}
