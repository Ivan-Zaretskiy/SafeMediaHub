<?php

use Dotenv\Dotenv;

const __MAINDIR__ = __DIR__ . '/..';
const TIME_FORMAT = 'H:i:s';
const DATE_INTERFACE_FORMAT = 'd.m.Y';
const DATETIME_INTERFACE_FORMAT = DATE_INTERFACE_FORMAT . ' ' . TIME_FORMAT;
const DATE_DATABASE_FORMAT = 'Y-m-d';
const DATETIME_DATABASE_FORMAT = DATE_DATABASE_FORMAT . ' ' . TIME_FORMAT;

require_once "vendor/autoload.php";
include_once "core/function/function_global.php";

$dotenv = Dotenv::createImmutable(__MAINDIR__);
$dotenv->load();

spl_autoload_register('autoload');
register_shutdown_function('handleShutdown');
