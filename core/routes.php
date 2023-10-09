<?php
$page = $_GET['page'] ?? 'homepage';
$action = $_GET['action'] ?? 'show';
switch ($page){
    case '404' :
        doError('Page not found!');
    default :
        $$page = new $page();
        break;
}
