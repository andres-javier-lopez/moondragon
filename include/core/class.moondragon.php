<?php

/**
 * @brief Clase núcleo del sistema
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup MoonDragon
 *
 */


class MoonDragon
{
	/**
	 * Registro de variables del sistema 
	 * @var array $registry
	 */
	public static $registry = array();

	/**
	 * Corre un objeto ejecutable del framework
	 * @param Runnable $object
	 * @return void
	 * @throws HeadersException
	 * @throws MoonDragonException
	 */
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

	/**
	 * Redirige un proceso en ejecución hacia un nueva url a través de headers
	 * @todo la implementación de esta función puede mejorarse
	 * @param string $url
	 * @return void
	 */
	public static function redirect($url) {
		self::$registry['redirection'] = $url;
	}

    /**
     * Compara si la versión proporcionada es compatible con la versión actual
     * @param string $version
     * @return boolean
     */
    public static function checkVersion($version) {
        if(strpos($version, '4e') !== false) {
            $compareversion = explode( '.', str_replace('4e', '', $version));
            $actualversion = explode('.', str_replace('4e', '', MOONDRAGON_VERSION));

            if(count($compareversion) == 2 && $compareversion[0] <= $actualversion[0]) {
                if($compareversion[0] == $actualversion[0]) {
                    if($compareversion[1] <= $actualversion[1]) {
                        return true;
                    }
                    else {
                        return false;
                    }
                }
                else {
                    return true;
                }
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }
}

// Fin de archivo
