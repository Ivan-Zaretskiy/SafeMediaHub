<?php

class SessionUser extends StaticObject {

    static function init() {
        parent::init();
    }

    static function setSessionUser($user_id) {
        $_SESSION['user_id'] = $user_id;
        self::setFromDBResult($user_id);
    }

    static function issetSession(): bool {
        return isset($_SESSION['user_id']);
    }
    static function getUserID(): int {
        return (int) self::get('id');
    }

    static function setFromDBResult($user_id): void {
        $dataUser = query('SELECT * FROM users WHERE id = ?', $user_id)->fetchRow();
        self::setObjectFromArray($dataUser);
        if (self::haveKey()) {
            self::setUserKey();
        }
    }

    static function getNextMode(): string {
        return self::getInterfaceMode() === 'light' ? 'dark' : 'light';
    }

    static function getModeIcon(): string {
        return self::getInterfaceMode() === 'light' ? 'moon-o' : 'sun-o';
    }

    static function isDarkMode(): bool {
        return (bool) self::get('dark_mode');
    }

    static function getInterfaceMode(): string {
        return self::isDarkMode() ? 'dark' : 'light';
    }

    static function setUserKey($key = null) {
        $key = $key ?? ($_SESSION['user']['key'] ?? null);
        self::set('key', $key);
        $_SESSION['user']['key'] = $key;
    }

    static function updateUserData() {
        self::setFromDBResult(self::getUserID());
    }

    static function haveKey(): bool {
        return (bool) self::get('have_key');
    }
}
