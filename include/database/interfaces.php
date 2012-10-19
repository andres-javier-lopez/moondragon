<?php

/**
 * Interface para instanciar una conexión con la base de datos
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @date Sep 2012
 * @version 1
 * @ingroup Database
 */

Interface DBConnection
{
	public function __construct($host, $user, $password, $database);
	
	public function checkConnection();
	
	public function getConnection();
	
	public function getManager();
}

/**
 * Interface para el manejo de operaciones en la base de datos
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @date Sep 2012
 * @version 1
 * @ingroup Database
 */

Interface DBManager
{
	public function __construct($connection);
	
	public function query($query, $limit = 0, $offset = 0);
	
	public function multiquery($multiquery);
	
	public function startTran();
	
	public function commit();
	
	public function rollback();
	
	public function showQueryHistory();
	
	public function insertId();
	
	public function evalSQL($value);
	
	public function getEmptyResult();
	
	public function getQuery($query, $params);
	
	public function getModel($config);
}


/**
 * Interface para el manejo de resultados de la base de datos
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @date Sep 2012
 * @version 1
 * @ingroup Database
 */

Interface DBResult extends Iterator
{
	public function fetch($type);
	
	public function getResult($field, $row = 0);
	
	public function numRows();
}

/**
 * Interface para el manejo de consultas en la base de datos
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @date Sep 2012
 * @version 1
 * @ingroup Database
 */

Interface DBQuery
{
	public function __construct($manager, $query, $params, $limit, $offset);
	
	public function setQuery($query);
	
	public function setLimit($limit, $offset);
	
	public function addParam($param);
	
	public function addParams($params, $replace);
	
	public function exec();
	
	public function getResult();
	
	public function clearResult();
}
