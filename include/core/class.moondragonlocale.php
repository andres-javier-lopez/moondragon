<?php

class MoonDragonLocale
{
	public static function init($locale = 'es_SV', $path = './locale') {
		putenv("LANG=$locale.utf-8");
		setlocale(LC_ALL, "$locale.utf-8");
		
		if(function_exists('bindtextdomain') && function_exists('textdomain')) {
			$dom = bindtextdomain("messages", realpath($path));
			textdomain("messages");
			assert('textdomain(NULL) == "messages"');
			assert('$dom == realpath($path)');
		}
	} 
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
