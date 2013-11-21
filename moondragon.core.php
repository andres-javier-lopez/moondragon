<?php

/**
 * @defgroup MoonDragon MoonDragon PHP
 * @brief Módulo central del framework
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * 
 * @exception MoonDragonException Excepción general del sistema
 * @exception Status404Exception Excepción para procesar errores 404
 * @exception HeadersException Excepción para procesos de header
 * @exception RequestException Excepción para variables recibidas por POST o GET
 */

define('MOONDRAGON_VERSION', '4e13.1');

if(!defined('MOONDRAGON_PATH')) {
    define('MOONDRAGON_PATH', dirname(__FILE__));
}

set_include_path(get_include_path() . PATH_SEPARATOR . MOONDRAGON_PATH);

// Error Control
assert_options(ASSERT_BAIL, true);

// Manejo de excepciones no capturadas
function moondragon_exception_handler($e) {
	if($e instanceof MoonDragonException) {
		$e->showException();
	}
	else {
		echo '<pre>'.$e.'</pre>';
	}
}
set_exception_handler('moondragon_exception_handler');

assert('defined("MOONDRAGON_PATH")');
assert('strpos(get_include_path(), PATH_SEPARATOR.MOONDRAGON_PATH) !== false');

require_once 'include/core/class.moondragonlocale.php';
require_once 'include/core/exceptions.php';
require_once 'include/core/interfaces.php';
require_once 'include/core/class.moondragon.php';
require_once 'include/core/class.buffer.php';
require_once 'include/core/class.request.php';
