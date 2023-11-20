<?php
class MemcacheHelper {
    private static $memcache;
    protected static string $host = 'memcached';
    protected static int $port = 11211;

    public static function init() {
        if (!self::$memcache) {
            self::$memcache = new Memcached();
            self::$memcache->addServer(self::$host, self::$port);
        }
    }

    public static function set($key, $value, $expire = 0) {
        self::init();
        return self::$memcache->set($key, $value, $expire);
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
