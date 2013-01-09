<?php

/**
 * Interface para instanciar una conexión con la base de datos
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
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
	
	public function getQuery($query, $params = array());
	
	public function getStatement($query);
	
	public function getModel($config = array());
}


/**
 * Interface para el manejo de resultados de la base de datos
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup Database
 */

Interface DBResult extends Iterator
{
	public function fetch($type = 'object');
	
	public function getResult($field, $row = 0);
	
	public function numRows();
}

/**
 * Interface para el manejo de consultas en la base de datos
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup Database
 */

Interface DBQuery
{
	public function __construct($manager, $query = '', $params = array(), $limit = 0, $offset = 0);
	
	public function setQuery($query);
	
	public function setLimit($limit, $offset = 0);
	
	public function addParam($param);
	
	public function addParams($params, $replace = false);
	
	public function exec();
	
	public function getResult();
	
	public function clearResult();
}

/**
 * Interface para el manejo de sentencias preparadas en la base de datos
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup Database
 */

Interface DBStatement
{
	public function __construct($manager, $query = '');
	
	public function prepareQuery($query);
	
	public function bindParam($type, &$param);
		
	public function exec();
		
	public function getResult();
}
