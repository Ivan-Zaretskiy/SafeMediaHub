<?php

class KeyHelper {

    public static $key;
    private static string $cipher = "AES-128-CBC";
    private static string $keyUrl = "keys.env";
    private static int $options = OPENSSL_RAW_DATA;
    private static $ivlen;
    private static int $sha2len = 32;
    private static int $bitesCount = 1024;

    static function init() {
        self::$ivlen = openssl_cipher_iv_length(self::$cipher);
        if (!file_exists(self::$keyUrl)) self::generateKey();
        self::setKey(self::getKey());
    }

    static function encryptString($text, $customKey = null) {
        $key = $customKey ?? self::getKey();
        if (!$key) return false;
        $iv = openssl_random_pseudo_bytes(self::$ivlen);
        $ciphertext_raw = openssl_encrypt($text, self::$cipher, $key, self::$options, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $key, true);

        return base64_encode( $iv.$hmac.$ciphertext_raw );
    }

    static function decryptString($text, $customKey = null) {
        $key = $customKey ?? self::getKey();
        if (!$key) return false;
        $c = base64_decode($text);
        $iv = substr($c, 0, self::$ivlen);
        $hmac = substr($c, self::$ivlen, self::$sha2len);
        $ciphertext_raw = substr($c, self::$ivlen + self::$sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, self::$cipher, $key, self::$options, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, true);

        return hash_equals($hmac, $calcmac) ? $original_plaintext : false;
    }

    static function generateKey() {
        $myFile = fopen(self::$keyUrl, "w") or doError("Unable to open file!");
        $txt = self::generateKeyString();
        fwrite($myFile, $txt);
        fclose($myFile);

        return $txt;
    }

    static function generateKeyString(): string {
        return openssl_random_pseudo_bytes(self::$bitesCount);
    }

    static function readKey() {
        $myFile = fopen(self::$keyUrl, "r") or doError("Unable to open file!");
        return fgets($myFile);
    }

    static function getKey() {
        return SessionUser::haveKey() ? SessionUser::get('key') : self::readKey();
    }

    static function setKey($key) {
        self::$key = $key;
    }
}