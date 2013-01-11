<?php

/**
 * @defgroup Database Database
 * @brief Módulo para manejo de bases de datos
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License 
 * @ingroup MoonDragon
 * 
 * @exception DatabaseException Excepción general de base de datos
 * @exception BadConnectionException Error de conexión
 * @exception QueryException Error en la consulta a la base de datos
 * @exception StatementException Error durante una sentencia preparada
 * @exception EmptyResultException La consulta no devolvió resultados
 * @exception ModelException Error en un proceso básico del modelo
 * @exception ReadException Error durante una consulta de lectura
 * @exception CreateException Error durante una consulta de inserción
 * @exception UpdateException Error durante una consulta de actualización
 * @exception DeleteException Error durante una consulta de eliminación
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
