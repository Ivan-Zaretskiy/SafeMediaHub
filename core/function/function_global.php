<?php
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
