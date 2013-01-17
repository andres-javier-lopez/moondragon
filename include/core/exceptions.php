<?php

/**
 * @brief Excepción general del sistema
 * 
 * Todas las excepciones pertenecientes a MoonDragon son derivadas de esta
 */
class MoonDragonException extends Exception {}


/**
 * @brief Excepción para procesar errores 404
 * 
 * Es manejada automáticamente por el sistema para mostrar la página de error
 */
class Status404Exception extends MoonDragonException {
	/**
	 * Muestra la pantalla de error 404
	 * @return void
	 */
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
		
		if(file_exists('404.php')) {
			$message = $this->getMessage();
			include '404.php';
		}
		if(file_exists('404.html') && filesize('404.html') > 0) {
			$string = file_get_contents('404.html');
			echo str_replace('[#:message]', $this->getMessage(), $string);
		}
		else {
			echo '<h1>404 Page Not Found</h1>';
			echo '<p>'.$this->getMessage().'</p>';
		}
	}	
}

/**
 * @brief Excepción para procesos de header
 * 
 * Indica un error en la configuración de las cabeceras
 */
class HeadersException extends MoonDragonException {}

/**
 * @brief Excepción para variables recibidas por POST o GET
 * 
 * Este error indica que la variable que se quizo obtener no fue recibida
 */
class RequestException extends MoonDragonException {}

// Fin del archivo
