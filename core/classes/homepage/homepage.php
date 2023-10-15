<?php
class homepage {

    public function show() {
        include_once('attaches/show_homepage.php');
    }

    public function loadMain() {
        include_once('attaches/main.php');
    }

    public function switchMode() {
        $mode = !SessionUser::isDarkMode();
        query("
        UPDATE
            users
        SET
            dark_mode = :mode
        WHERE
            id = :id
        ", [
            ':mode' => $mode,
            ':id' => SessionUser::getUserID()
        ])->execute();
        redirect('/');
    }
}
