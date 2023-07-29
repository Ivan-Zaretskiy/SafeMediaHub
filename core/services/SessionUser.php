<?php

class SessionUser extends CustomObject {

    public function __construct($user_id = null)
    {
        parent::__construct();
        $user_id = $user_id ?? $_SESSION['user']['id'];
        $this->setFromDBResult($user_id);
    }

    public function getUserID(): int
    {
        return (int) $this->get('id');
    }

    function setFromDBResult($user_id): void
    {
        $dataUser = query('SELECT * FROM `users` WHERE id = ?', $user_id)->fetchRow();
        foreach ($dataUser as $key => $value) {
            $this->$key = $value;
        }
        if ($this->haveKey()) {
            $this->setUserKey();
        }
    }

    function getNextMode(): string
    {
        return !$this->isDarkMode() ? 'dark' : 'light';
    }

    function getModeIcon(): string
    {
        return !$this->isDarkMode() ? 'moon-o' : 'sun-o';
    }

    function isDarkMode(): bool
    {
        return (bool) $this->get('dark_mode');
    }

    function getInterfaceMode(): string
    {
        return $this->isDarkMode() ? 'dark' : 'light';
    }

    function setUserKey($key = null)
    {
        $key = $key ?? ($_SESSION['user']['key'] ?? null);
        $this->set('key', $key);
        $_SESSION['user']['key'] = $key;
    }

    function updateUserData()
    {
        $this->setFromDBResult($this->getUserID());
    }

    function haveKey(): bool
    {
        return (bool) $this->get('have_key');
    }
}
