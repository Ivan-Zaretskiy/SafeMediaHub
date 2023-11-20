<?php

//phpinfo();
//die();

include_once ('core/config.php');
ApplicationHelper::init();
ApplicationHelper::handle();
ApplicationHelper::exit();