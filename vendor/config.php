<?php
session_start();
mb_internal_encoding("UTF-8");
include_once "vendor/db_connection.php";
include_once "vendor/db/db_function.php";
include_once "vendor/function/function_global.php";
include_once "vendor/classes/SessionUser.php";
include_once "vendor/classes/login/login.php";
include_once "vendor/classes/keysManager/keysManager.php";
include_once "routes.php";
$loginManager = new login();
$user = new SessionUser();
$keyManager = new keysManager();
