<?php
include_once "keysManager.php";
include_once "vendor/db_connection.php";
include_once "vendor/db/db_function.php";
$google_pass = "X58PNEW74COLDQ228ZNXXL";
echo '<pre>';
var_dump(getArrayQuery("SELECT * FROM `encryptedString`"));
echo '</pre>';
