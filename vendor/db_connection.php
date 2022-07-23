<?php
$DB_HOST = 'localhost';
$DB_USER = 'vanya';
$DB_PASSWORD = 'root';
$DB_NAME = 'password_manager';

$mysqli = new mysqli($DB_HOST,$DB_USER,$DB_PASSWORD,$DB_NAME);
if ($mysqli->connect_errno){
    die($mysqli->connect_error);
}
