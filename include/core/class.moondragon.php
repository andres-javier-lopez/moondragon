<?php

class MoonDragon
{
	public static $registry = array();
	
	public static function run(Runnable $object) {
		try {
			$object->run();
			
			if(isset(self::$registry['redirection'])) {
				if(!headers_sent()) {
					header('Location: '.self::$registry['redirection']);
				}
				else {
					throw new HeadersException();
				}
			}
		}
		catch(Status404Exception $e) {
			if(!headers_sent()) {
				if(function_exists('http_response_code')) {
					http_response_code(404);
				}
				else {
					header('HTTP/1.0 404 Not Found');
					header('Status: 404 Page Not Found');
				}
				echo $e->show404();
			}
			else {
				throw new HeadersException();
			}
		}		
	}
	
	public static function redirect($url) {
		self::$registry['redirection'] = $url;
	}
}