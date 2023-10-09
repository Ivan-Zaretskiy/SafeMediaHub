<?php
class homepage {

    public function show() {
        global $user;

        include_once('attaches/show_homepage.php');
    }

    public function loadMain() {
        include_once('attaches/main.php');
    }

    public function switchMode() {
        global $user;

        $mode = $user->dark_mode == 0 ? 1 : 0;
        query("
        UPDATE
            users
        SET
            dark_mode = :mode
        WHERE
            id = :id
        ", [
            ':mode' => $mode,
            ':id' => $user->getUserID()
        ])->execute();
        redirect('/');
    }
}
