<?php

class login{
    public $loginUser;

    public function __construct()
    {
        if ($_GET['action'] == 'sign_up'){
            $this->sign_up();
        }
        if ($_GET['action'] == 'logout'){
            $this->logout();
        }
        if ($_GET['action'] == 'createNewAccount'){
            $this->createNewAccount();
        }
        if (empty($_SESSION['loginUser'])) {
            if (!empty($_POST['email']) && !empty($_POST['password'])) {
                $email = mres($_POST['email']);
                $pass = $_POST['password'];
                $user_info = getRowQuery('SELECT * FROM users WHERE `email` = "'.$email.'"');
                if ($user_info) {
                    $pass_verify = password_verify($pass, $user_info['password']);
                    if ($pass_verify) {
                        $this->loginUser = $user_info;
                        $_SESSION['loginUser'] = $user_info;
                    } else {
                        $this->sign_in('Invalid password');
                    }
                } else {
                    $this->sign_in('This user doesn\'t exist');
                }
            } else {
                $this->sign_in();
            }
        } else {
            $user_info = getRowQuery('SELECT * FROM users WHERE `id` = '.$_SESSION['loginUser']['id']);
            $_SESSION['loginUser'] = $user_info;
            $this->loginUser = $user_info;
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
        $options_hash = [
            'cost' => 12,
        ];
        $hash_password = password_hash($password,PASSWORD_BCRYPT,$options_hash);
        $q = 'INSERT INTO `users` SET
                  `username` = "'.$username.'",
                  `email` = "'.$email.'",
                  `password` = "'.$hash_password.'"';
        if(mq($q)){
            redirect('/');
        } else {
            $error = 'Problem with saving user';
            $this->sign_up($error);
        }
    }
}
