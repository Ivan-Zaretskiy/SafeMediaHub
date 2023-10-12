<?php
class MemcacheHelper {
    private static $memcache;

    public static function init() {
        if (!self::$memcache) {
            self::$memcache = new Memcache;
            self::$memcache->addServer('localhost', 11211);
        }
    }

    public static function set($key, $value, $expire = 0) {
        self::init();
        return self::$memcache->set($key, $value, 0, $expire);
    }

    public static function get($key) {
        self::init();
        return self::$memcache->get($key);
    }

    public static function delete($key) {
        self::init();
        return self::$memcache->delete($key);
    }

    public static function flush() {
        self::init();
        return self::$memcache->flush();
    }

    public static function close() {
        if (self::$memcache) {
            self::$memcache->close();
        }
    }
}
