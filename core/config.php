<?php
session_start();
mb_internal_encoding("UTF-8");
include_once "core/define.php";
include_once "core/db_connection.php";
include_once "core/db/db_function.php";
include_once "core/services/CustomObject.php";
include_once "core/db/PDO_Connection.php";
include_once "core/db/PDO_Service.php";
$PDO = new PDO_Connection();
include_once "core/function/function_global.php";
include_once "core/services/SessionUser.php";
include_once "core/classes/login/login.php";
include_once "core/classes/keysManager/keysManager.php";
include_once "routes.php";
$loginManager = new login();
$user = new SessionUser();
$keyManager = new keysManager();
//Additional Services
