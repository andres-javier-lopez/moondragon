<?php

/**
 * @brief Clase de Localizacion de idiomas
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup MoonDragon
 */

class MoonDragonLocale
{
	/**
	 * Inicializa los valores de configuración de la localización
	 * @param string $locale
	 * @param string $path
	 * @return void
	 */
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

// Fin de archivo
