<?php

/**
 * @defgroup Manager Manager
 * @brief Módulo para manejo de procesos
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup MoonDragon
 * 
 * @exception ManagerException Excepción general del módulo
 * @exception TaskException Error 404 por tareas no encontradas
 * @exception RouteException Error 404 por rutas no encontradas
 */

if(!defined('MOONDRAGON_PATH')) {
    define('MOONDRAGON_PATH', dirname(__FILE__));
    set_include_path(get_include_path() . PATH_SEPARATOR . MOONDRAGON_PATH);
}

assert("defined('MOONDRAGON_PATH')");

require_once 'moondragon.core.php';

require_once 'include/manager/exceptions.php';
require_once 'include/manager/class.manager.php';
require_once 'include/manager/class.router.php';
require_once 'include/manager/class.jsonmanager.php';
