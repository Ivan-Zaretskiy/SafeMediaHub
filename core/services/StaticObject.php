<?php
class StaticObject {

    private static stdClass $data;

    static function init() {
        self::$data = new stdClass();
    }

    static function set($name, $value = null) {
        self::$data->$name = $value;
    }

    static function get($name, $returnValue = null) {
        if (self::exist($name) && $returnValue && !self::$data->$name) {
            return $returnValue;
        }
        return self::exist($name) ? self::$data->$name : $returnValue;
    }

    public function __get($name) {
        return self::get($name);
    }

    static function exist($name): bool {
        return property_exists(self::$data, $name);
    }

    public function delete($name) {
        unset(self::$data->$name);
    }

    static function setObjectFromArray($array) {
        foreach ($array as $name => $value) {
            self::set($name, $value);
        }
    }
}