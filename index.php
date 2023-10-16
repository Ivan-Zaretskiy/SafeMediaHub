<?php
include_once ('core/config.php');
switch (ApplicationHelper::getAppMode()) {
    case 'main':
        include_once("templates/main.php");
        break;
    case 'load':
        include_once("templates/load.php");
        break;
    default:
        redirect('/index.php?page=404');
        break;
}
ApplicationHelper::exit();