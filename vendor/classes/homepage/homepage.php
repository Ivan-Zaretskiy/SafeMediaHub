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
}
