<?php

$locale = isset($_GET['lang'])?$_GET['lang']:'es_SV';

putenv("LANG=$locale.utf-8");
setlocale(LC_ALL, "$locale.utf-8");

if(function_exists('bindtextdomain') && function_exists('textdomain')) {
	$dom = bindtextdomain("messages", realpath(MOONDRAGON_PATH."/locale"));
	textdomain("messages");
	assert('textdomain(NULL) == "messages"');
	assert('$dom == realpath(MOONDRAGON_PATH."/locale")');
}

if(!function_exists('gettext')) {
	trigger_error('No hay soporte para gettext', E_USER_WARNING);
	function _($string) {
		return $string;
	}
	
	function gettext($string) {
		return $string;
	}
}

assert('function_exists("_")');
