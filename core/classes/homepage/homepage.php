<?php
class homepage {
    public $user;

    public function __construct()
    {

    }

    public function show()
    {
        global $user;

        include_once('attaches/show_homepage.php');
    }

    public function loadMain()
    {
        include_once('attaches/main.php');
    }

    public function switchMode()
    {
        global $user;

        $mode = $user->dark_mode == 0 ? 1 : 0;
        mq('UPDATE `users` SET `dark_mode` = ' .$mode. ' WHERE `id` = ' .$user->getUserID());
        redirect('/');
    }
}
