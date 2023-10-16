<?php
const __MAINDIR__ = __DIR__ . '/..';
const TIME_FORMAT = 'H:i:s';
const DATE_INTERFACE_FORMAT = 'd.m.Y';
const DATETIME_INTERFACE_FORMAT = DATE_INTERFACE_FORMAT . ' ' . TIME_FORMAT;
const DATE_DATABASE_FORMAT = 'Y-m-d';
const DATETIME_DATABASE_FORMAT = DATE_DATABASE_FORMAT . ' ' . TIME_FORMAT;

require_once "vendor/autoload.php";
require_once "services/ApplicationHelper.php";
require_once "services/MemcacheHelper.php";
require_once "services/StaticObject.php";
require_once "services/SessionUser.php";
require_once "services/PDOHelper.php";
require_once "services/KeyHelper.php";