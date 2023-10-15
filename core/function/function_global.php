<?php

function showLoader(): string
{
    return '<div class="loader"></div alt="LOADER">';
}

function redirect($url = '/')
{
    header('Location: '.$url);
    exit();
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

function doError($message): void {
    trigger_error($message, E_ERROR);
}
