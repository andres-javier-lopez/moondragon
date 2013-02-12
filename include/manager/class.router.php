<?php

/**
 * @brief Clase para manejo de ruteo de urls
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup Manager
 */

class Router
{
	/**
	 * Lista de archivos que almacenan los controladores
	 * @var array
	 */
	private static $files = array();

	/**
	 * Lista de controladores del sistema
	 * @var array
	 */
	private static $managers = array();

	/**
	 * Agrega la ruta de un controlador al sistema
	 * @param string $identifier
	 * @param string $file
	 * @param string $manager Si el manager esta vacío solo se ejecuta el archivo
	 * @param string $default
	 * @return void
	 */
	public static function addSection($identifier, $file, $manager = '', $default = false) {
		self::$files[$identifier] = $file;
		self::$managers[$identifier] = $manager;
		if($default) {
			self::$files['index'] = $file;
			self::$managers['index'] = $manager;
		}
		assert('isset(self::$files[$identifier]) && isset(self::$managers[$identifier])');
	}

	/**
	 * Obtiene la información de una ruta
	 * @param string $identifier
	 * @throws RouteException
	 */
	public static function getSection($identifier) {
		if(isset(self::$files[$identifier]) && isset(self::$managers[$identifier])) {
			$result = new stdClass();
			$result->file = self::$files[$identifier];
			$result->manager = self::$managers[$identifier];
			assert('isset($result->file) && isset($result->manager)');
				
			return $result;
		}
		else {
			throw new RouteException(_('No se encontró la sección'));
		}
	}

	/**
	 * Proceso que evalúa la url de la petición para determinar el controlador que será ejecutado.
	 * Puede ser usado como proceso inicial del sistema.
	 * @return void
	 * @throws RouteException
	 */
	public static function enroute() {
		$requestURI = $_SERVER['REQUEST_URI'];
		$scriptName = $_SERVER['SCRIPT_NAME'];

		if($requestURI != '/') {
			if(strpos($requestURI, basename($scriptName)) !== false) {
				$baseURI = str_replace($scriptName, '', $requestURI);
			}
			else if(dirname($scriptName) != '/'){
				$baseURI = str_replace(dirname($scriptName), '', $requestURI);
			}
			else {
            	$baseURI = trim($requestURI, '/');
            }

				
			if(strpos($baseURI, basename($scriptName)) !== false) {
				throw new RouteException(_('La url no es válida'));
			}
				
			assert('strpos($baseURI, basename($scriptName)) === false');
		}
		else {
			$baseURI = '/';
		}

		if($baseURI == '' || $baseURI == '/') {
			$managerURI = $requestURI;
			$section = 'index';
		}
		else {
			list($section) = explode('/', trim($baseURI, '/'));
			$managerURI = str_replace($baseURI, '', trim($requestURI, '/')).'/'.$section;
		}
		// agregado control adicional para borrar diagonales duplicadas
		$managerURI = str_replace('//', '/', $managerURI);

		$conf = self::getSection($section);

		include_once $conf->file;
		if($conf->manager != '' && class_exists($conf->manager) && is_subclass_of($conf->manager, 'Manager')) {
			$manager_class = $conf->manager;
			$manager = new $manager_class($managerURI);
			assert('$manager instanceof $conf->manager && $manager instanceof Manager');
			MoonDragon::run($manager);
		}
	}
}

// Fin del archivo
