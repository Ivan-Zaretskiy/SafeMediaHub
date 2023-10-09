<?php

use JetBrains\PhpStorm\NoReturn;

function showLoader(): string
{
    return '<div class="loader"></div alt="LOADER">';
}

function redirect($url = '/')
{
    header('Location: '.$url);
    die();
}

function query($sql, $params = null, $setCurrentConnection = true): PDO_Service {
    global $PDO;
    $query = new PDO_Service($sql);
    if ($params) {
        if (is_array($params)) {
            foreach ($params as $key => $value) {
                $query->setParam($key, $value);
            }
        } else {
            $query->setParam(0, $params);
        }
    }
    if ($setCurrentConnection) {
        $query->setConnection($PDO);
    }
    return $query;
}

function autoload($name): bool {
    $dirs = [
        __MAINDIR__ . '/core/classes/'.$name.'/'.$name.'.php',
        __MAINDIR__ . '/core/db/'.$name.'.php',
        __MAINDIR__ . '/core/services/'.$name.'.php',
    ];
    foreach ($dirs as $dir) {
        if (file_exists($dir)) {
            include_once $dir;
            return true;
        }
    }
    return false;
}

function handleShutdown() {
    $error = error_get_last();
    if ($error !== null) {
        $errorType = $error['type'];
        $errorMessage = $error['message'];
        $errorFile = $error['file'];
        $errorLine = $error['line'];


        $errorText = "Ошибка типа $errorType: $errorMessage в файле $errorFile на строке $errorLine" . PHP_EOL;;
        $errorAppend = "-------------------------" . PHP_EOL;

        file_put_contents('core/logs/log_'.date("d.m.Y").'.log', $errorText . $errorAppend, FILE_APPEND);

        exit;
    }
}

function doError($message): void {
    trigger_error($message, E_ERROR);
}
