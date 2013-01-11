<?php

/**
 * @defgroup Caller Caller
 * @brief Módulo para realizar llamadas a webservices de tipo RESTful
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup MoonDragon
 * 
 * @exception CallerException Excepción general del módulo
 * @exception BadApiUrlException Error con el URL del API
 * @exception EmptyDataException Los datos de envío están vacíos
 * @exception CurlException Error en proceso de librería curl
 * @exception JsonException Error en procesamiento de JSON
 */

if(!defined('MOONDRAGON_PATH')) {
	define('MOONDRAGON_PATH', dirname(__FILE__));
	set_include_path(get_include_path() . PATH_SEPARATOR . MOONDRAGON_PATH);
}

assert("defined('MOONDRAGON_PATH')");

require_once 'moondragon.core.php';

define('GET_CALL', 'get');
define('POST_CALL', 'post');
define('PUT_CALL', 'put');
define('DELETE_CALL', 'delete');
define('DATA_JSON', 'application/json');

require_once 'include/caller/exceptions.php';
require_once 'include/caller/class.caller.php';
require_once 'include/caller/class.json.php';
