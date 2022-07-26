<?php
class homepage {
    public $user;

    public function __construct()
    {

    }

    public function show()
    {
        include_once('attaches/show_homepage.php');
    }

    public function loadMain()
    {
        include_once('attaches/main.php');
    }

    public function switchMode()
    {
        $mode = $_SESSION['loginUser']['dark_mode'] == 0 ? 1 : 0;
        mq('UPDATE `users` SET `dark_mode` = ' .$mode. ' WHERE `id` = ' .$_SESSION['loginUser']['id']);
        redirect('/');
    }
}
