<?php

/**
 * @defgroup Database Módulo para manejo de bases de datos
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License 
 * @ingroup MoonDragon
 */

if(!defined('MOONDRAGON_PATH')) {
	define('MOONDRAGON_PATH', dirname(__FILE__));
	set_include_path(get_include_path() . PATH_SEPARATOR . MOONDRAGON_PATH);
}

assert("defined('MOONDRAGON_PATH')");

require_once 'moondragon.core.php';

require_once 'include/database/exceptions.php';
require_once 'include/database/interfaces.php';
require_once 'include/database/class.database.php';
require_once 'include/database/class.basictable.php';
require_once 'include/database/class.tabledata.php';
require_once 'include/database/class.foreigntable.php';
require_once 'include/database/class.dataset.php';
require_once 'include/database/class.reader.php';
require_once 'include/database/class.model.php';
