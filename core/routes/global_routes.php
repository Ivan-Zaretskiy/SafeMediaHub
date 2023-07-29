<?php
switch ($page){
    case '': break;
    default : $$page->$action(); break;
}
