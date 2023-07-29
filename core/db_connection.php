<?php
$mysqli = new mysqli($_ENV['DB_LOCAL_HOST'], $_ENV['DB_LOCAL_USER'], $_ENV['DB_LOCAL_PASSWORD'], $_ENV['DB_LOCAL_NAME']);
if ($mysqli->connect_errno){
    die($mysqli->connect_error);
}
