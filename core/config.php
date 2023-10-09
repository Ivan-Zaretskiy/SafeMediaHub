<?php
session_start();
mb_internal_encoding("UTF-8");

include_once "core/define.php";
include_once "core/function/function_global.php";

$PDO = new PDO_Connection();
$loginManager = new login();
$user = new SessionUser();
$keyManager = new keysManager();
