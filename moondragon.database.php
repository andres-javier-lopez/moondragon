<?php

if(!defined('MOONDRAGON_PATH')) {
	define('MOONDRAGON_PATH', dirname(__FILE__));
	set_include_path(get_include_path() . PATH_SEPARATOR . MOONDRAGON_PATH);
}

assert("defined('MOONDRAGON_PATH')");

require_once 'moondragon.core.php';

require_once 'include/database/class.dbconnection.php';
require_once 'include/database/class.dbmanager.php';
require_once 'include/database/class.dbquery.php';
require_once 'include/database/class.dbresult.php';