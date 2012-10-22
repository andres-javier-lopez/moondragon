<?php

/**
 * @defgroup MySQL Manejadores para base de datos MySQL
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @date Sep 2012
 * @version 1.0
 * @ingroup Database
 */

define('SC', '`'); // Separador de campos
define('SV', '"'); // Separador de valores


require_once 'include/database/mysql/class.mysqlconnection.php';
require_once 'include/database/mysql/class.mysqlmanager.php';
require_once 'include/database/mysql/class.mysqlresult.php';
require_once 'include/database/mysql/class.mysqlquery.php';
