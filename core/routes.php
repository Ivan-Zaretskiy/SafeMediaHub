<?php
foreach ($_GET as $key=>$value){$$key = $value;}
$page = $page ?? 'homepage';
$action = $action ?? 'show';
switch ($page){
    case '404' :
        include_once ('404.php');
        die();
    default :
        if( file_exists(__DIR__.'/classes/'.$page.'/'.$page.'.php') && !class_exists($page) ){
            require_once(__DIR__.'/classes/'.$page.'/'.$page.'.php');
        }
        if(!@is_object($$page) ){
            if(class_exists($page)){
                $$page = new $page();
            }else{
                include_once ('404.php'); die();
            }
        }
        break;
}
