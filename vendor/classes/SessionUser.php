<?php

class SessionUser{
    public array $user;

    public function __construct($user_id = null)
    {
        $user_id = $user_id ?? $_SESSION['user_id'];
        unset($_SESSION['user_id']);
        $data_user = getRowQuery('SELECT * FROM `users` WHERE id = ' . (int)$user_id);
        $this->user = $data_user;
        $this->set_array($data_user);
        $_SESSION['user'] = $this->getUser();
        if ($this->have_key == 1) $this->setUserKey();
    }

    public function getUser(): array
    {
        return $this->user;
    }

    public function getUserID(): int
    {
        return (int) $this->user['id'];
    }

    function set_array(array $array): void
    {
        foreach ($array as $key => $value) {
            $this->$key = $value;
        }
    }

    function set($key, $value)
    {
        return $this->$key = $value;
    }

    function getNextMode(): string
    {
        return $this->dark_mode == 0 ? 'dark' : 'light';
    }

    function getModeIcon(): string
    {
        return $this->dark_mode == 0 ? 'moon-o' : 'sun-o';
    }

    function isDarkMode(): bool
    {
        return $this->dark_mode == 1;
    }

    function getInterfaceMode(): string
    {
        return $this->dark_mode == 1 ? 'dark' : 'light';
    }

    function setUserKey($key = null)
    {
        $key = $key ?? ($_SESSION['user_key'] ?? null);
        $_SESSION['user_key'] = $this->set('key', $key);
    }

    function updateUserData()
    {
        $data_user = getRowQuery('SELECT * FROM `users` WHERE id = ' . $this->getUserID());
        $this->user = $data_user;
        $this->set_array($data_user);
        $_SESSION['user'] = $this->getUser();
        if ($this->have_key == 1) $this->setUserKey();
    }
}
