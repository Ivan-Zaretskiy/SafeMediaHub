<?php

function showLoader(): string {
    return '<div class="loader"></div alt="LOADER">';
}

function redirect($url = '/'): void {
    header('Location: '.$url);
    ApplicationHelper::exit();
}

function query($sql, $params = null, $setCurrentConnection = true): PDO_Service {
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
        $query->setConnection(PDOHelper::getPDOConnection());
    }
    return $query;
}

function doError($message) {
    trigger_error($message, E_USER_ERROR);
}

function writeLog($text, $file = null): false|int {
    $path = 'logs/';
    mkdir($path, 0755, true);
    $file = $file ?? "log_" . date('d.m.Y') . ".log";

    return file_put_contents($path . $file, $text, FILE_APPEND);
}