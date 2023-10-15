<?php

class login {

    public function __construct() {
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            if (in_array($action, ['sign_up', 'logout', 'createNewAccount'])) {
                $this->$action();
            }
        }
        if (SessionUser::issetSession()) {
            SessionUser::setSessionUser($_SESSION['user_id']);
        } else {
            if (!empty($_POST['email']) && !empty($_POST['password'])) {
                $email = $_POST['email'];
                $pass = $_POST['password'];
                $user_info = query('SELECT * FROM users WHERE email = ?', $email)->fetchRow();
                if ($user_info && password_verify($pass, $user_info->password)) {
                    SessionUser::setSessionUser($user_info->id);
                } else {
                    $this->sign_in('Invalid email or password');
                }
            } else {
                $this->sign_in();
            }
        }
        $this->afterLogin();
    }

    public function sign_up($error = false) {
        $type = 'sign_up';
        include_once 'attaches/login_form.php';
    }

    public function sign_in($error = false) {
        $type = 'sign_in';
        include_once 'attaches/login_form.php';
    }

    public function logout() {
        unset($_SESSION);
        session_destroy();
        redirect('/');
    }

    public function createNewAccount() {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $firstPIN = $_POST['firstPIN'];
        $secondPIN = $_POST['secondPIN'];
        if ($password !== $confirm_password){
            $this->sign_up('Password must to match');
        }
        $check_username = query('SELECT 1 FROM users WHERE username = ?', $username)->fetchCell();
        $check_email = query('SELECT id FROM users WHERE email = ?', $email)->fetchCell();
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
        query('
        INSERT INTO
            users
        SET
            username = :username,
            email = :email,
            firstPIN = :firstPIN,
            secondPIN = :secondPIN,
            password = :password
        ', [
            ':username' => $username,
            ':email' => $email,
            ':firstPIN' => password_hash($firstPIN,PASSWORD_BCRYPT, ['cost' => 12]),
            ':secondPIN' => password_hash($secondPIN,PASSWORD_BCRYPT, ['cost' => 12]),
            ':password' => password_hash($password,PASSWORD_BCRYPT, ['cost' => 12]),
        ])->execute();
        redirect('/');
    }

    private function afterLogin() {}
}
