<?php
session_start();
mb_internal_encoding("UTF-8");
ini_set('session.gc_maxlifetime', 3600);

include_once "core/define.php";
include_once "core/function/function_global.php";