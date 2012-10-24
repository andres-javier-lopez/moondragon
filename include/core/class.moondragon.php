<?php

/**
 * Clase núcleo del sistema
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @date Sep 2012
 * @version 1
 * @ingroup MoonDragon
 */


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
			$e->show404();
		}		
	}
	
	public static function redirect($url) {
		self::$registry['redirection'] = $url;
	}
}