<?php

/**
 * Clase para manejo de ruteo de urls
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup Manager
 */

class Router
{	
	private static $files = array();
	
	private static $managers = array();
	
	public static function addSection($identifier, $file, $manager = '', $default = false) {
		self::$files[$identifier] = $file;
		self::$managers[$identifier] = $manager;
		if($default) {
			self::$files['index'] = $file;
			self::$managers['index'] = $manager;
		}
		assert('isset(self::$files[$identifier]) && isset(self::$managers[$identifier])');
	}
	
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
	
	public static function enroute() {
		$requestURI = $_SERVER['REQUEST_URI'];
		$scriptName = $_SERVER['SCRIPT_NAME'];
		
		if(dirname($scriptName) != '/') {
			if(strpos($requestURI, basename($scriptName)) !== false) {
				$baseURI = str_replace($scriptName, '', $requestURI);
			}
			else {
				$baseURI = str_replace(dirname($scriptName), '', $requestURI);
			}
					
			if(strpos($baseURI, basename($scriptName)) !== false) {
				throw new RouteException(_('La url no es válida'));
			}
			
			assert('strpos($baseURI, basename($scriptName)) === false');
		}
		
		if($baseURI == '' || $baseURI == '/') {
			$managerURI = $requestURI;
			$section = 'index';
		}
		else {
			list($section) = explode('/', trim($baseURI, '/'));
			$managerURI = str_replace($baseURI, '', $requestURI).'/'.$section;			
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