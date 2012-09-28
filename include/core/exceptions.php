<?php

/// Excepción general de MoonDragon
/// @ingroup MoonDragon
class MoonDragonException extends Exception {}

/// Excepción para procesar errores 404
/// @ingroup MoonDragon
class Status404Exception extends MoonDragonException {
	public function show404() {
		if(!headers_sent()) {
			if(function_exists('http_response_code')) {
				http_response_code(404);
			}
			else {
				header('HTTP/1.0 404 Not Found');
				header('Status: 404 Page Not Found');
			}
		}
		
		if(file_exists('404.html')) {
			include '404.html';
		}
		else {
			echo '<h1>404 Page Not Found</h1>';
			echo '<p>'.$this->getMessage().'</p>';
		}
	}	
}

/// Excepción para procesos de Header
/// @ingroup MoonDragon
class HeadersException extends MoonDragonException {}
