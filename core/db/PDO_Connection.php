<?php
class PDO_Connection {
    private PDO $connection;

    public function __construct($mode = null) {
        $data = $this->getInstance($mode);
        try {
            $this->connection = new PDO("mysql:charset=utf8;host=".$data->HOST.";dbname=".$data->DBNAME, $data->USER, $data->PASSWORD);
        } catch (PDOException $exception) {
            die($exception);
        }
    }

    private function getInstance($mode = null): CustomObject {
        $mode = $this->getMode($mode);
        $data = new CustomObject();
        switch ($mode) {
            case 'LOCAL':
            default :
                $data->set('HOST', $_ENV['DB_'.$mode.'_HOST']);
                $data->set('DBNAME', $_ENV['DB_'.$mode.'_NAME']);
                $data->set('USER', $_ENV['DB_'.$mode.'_USER']);
                $data->set('PASSWORD', $_ENV['DB_'.$mode.'_PASSWORD']);
                break;
        }
        return $data;
    }

    private function getMode($mode) {
        if ($mode) {
            return $mode;
        }
        return $_SERVER['SERVER_NAME'] == 'localhost' ? $_ENV['DB_LOCAL_MODE'] : $_ENV['DB_PROD_MODE'];
    }

    public static function getConnection($mode): PDO_Connection {
        return new PDO_Connection($mode);
    }

    public function prepare($query) {
        return $this->connection->prepare($query);
    }
}
