<?php
class PDOHelper {
    private static PDO_Connection $PDO_Connection;

    static function init() {
        self::$PDO_Connection = new PDO_Connection();
    }

    static function getPDOConnection(): PDO_Connection {
        return self::$PDO_Connection;
    }
}