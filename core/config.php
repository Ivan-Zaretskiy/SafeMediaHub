<?php
ini_set('session.gc_maxlifetime', 3600);
session_start();
mb_internal_encoding("UTF-8");
ini_set('memory_limit', '512M');
//error_reporting(E_ALL & ~E_STRICT);
//ini_set('display_errors', '1');
ini_set('max_execution_time', '30');
mb_internal_encoding('utf-8');
mb_regex_encoding('utf-8');

include_once "core/define.php";
include_once "core/function/function_global.php";