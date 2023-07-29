<?php
const __MAINDIR__ = __DIR__ . '/..';
const INTERFACE_DB_FORMAT = 'd.m.Y H:i:s';
const DATE_DB_FORMAT = 'Y-m-d H:i:s';
const TIME_FORMAT = 'H:i:s';
require_once "vendor/autoload.php";
$dotenv = \Dotenv\Dotenv::createImmutable(__MAINDIR__);
$dotenv->load();
