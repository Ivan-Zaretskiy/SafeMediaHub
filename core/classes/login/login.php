<?php

class login{

    public function __construct()
    {
        $action = $_GET['action'];
        if (in_array($action, ['sign_up', 'logout', 'createNewAccount'])) {
            $this->$action();
        }
        if (empty($_SESSION['user']['id'])) {
            if (!empty($_POST['email']) && !empty($_POST['password'])) {
                $email = mres($_POST['email']);
                $pass = $_POST['password'];
                $user_info = getRowQuery('SELECT * FROM users WHERE `email` = "'.$email.'"');
                if ($user_info && password_verify($pass, $user_info['password'])) {
                    $_SESSION['user']['id'] = $user_info['id'];
                } else {
                    $this->sign_in('Invalid email or password');
                }
            } else {
                $this->sign_in();
            }
        }
    }

    public function sign_up($error = false)
    {
        $type = 'sign_up';
        include_once 'attaches/login_form.php';
        die();
    }

    public function sign_in($error = false)
    {
        $type = 'sign_in';
        include_once 'attaches/login_form.php';
        die();
    }

    public function logout()
    {
        unset($_SESSION);
        session_destroy();
        redirect('/');
    }

    public function createNewAccount()
    {
        $username = mres($_POST['username']);
        $email = mres($_POST['email']);
        $password = mres($_POST['password']);
        $confirm_password = mres($_POST['confirm_password']);
        $firstPIN = mres($_POST['firstPIN']);
        $secondPIN = mres($_POST['secondPIN']);
        if ($password !== $confirm_password){
            $this->sign_up('Password must to match');
        }
        $check_username = getValueQuery('SELECT id FROM users WHERE username = "'.$username.'"');
        $check_email = getValueQuery('SELECT id FROM users WHERE email = "'.$email.'"');
        if (!empty($check_username)){
            $this->sign_up('We have such username');
        }
        if (!empty($check_email)){
            $this->sign_up('We have such email');
        }
        if (empty($firstPIN) || !is_numeric($firstPIN) || strlen($firstPIN) !== 4) {
            $this->sign_up('Incorrect First PIN');
        }
        if (empty($secondPIN) || !is_numeric($secondPIN) || strlen($secondPIN) !== 8) {
            $this->sign_up('Incorrect Second PIN');
        }
        $q = 'INSERT INTO `users` SET
                  `username` = "'.$username.'",
                  `email` = "'.$email.'",
                  `firstPIN` = "'.password_hash($firstPIN,PASSWORD_BCRYPT, ['cost' => 12]).'",
                  `secondPIN` = "'.password_hash($secondPIN,PASSWORD_BCRYPT, ['cost' => 12]).'",
                  `password` = "'.password_hash($password,PASSWORD_BCRYPT, ['cost' => 12]).'"';
        if(mq($q)){
            redirect('/');
        } else {
            $error = 'Problem with saving user_settings';
            $this->sign_up($error);
        }
    }
}
