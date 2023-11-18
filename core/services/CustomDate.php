<?php

class CustomDate extends DateTime {
    public static function createFormatData($format, $string, $newFormat): ?string {
        if ($string) {
            return parent::createFromFormat($format, $string)->format($newFormat);
        }
        return null;
    }

    /**
     * @param string $date
     * @return null|string
     */
    public static function returnNullIfEmpty($date): ?string {
        if ($date === "") {
            return null;
        }
        return $date;
    }
}
