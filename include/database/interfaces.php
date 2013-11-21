<?php

/**
 * @brief Interface para instanciar una conexión con la base de datos
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup Database
 */

interface DBConnection
{
	/**
	 * Inicializa la conexión con la base de datos
	 * @param string $host
	 * @param string $user
	 * @param string $password
	 * @param string $database
	 * @return void
	 * @throws BadConnectionException
	 */
	public function __construct($host, $user, $password, $database);
	
	/**
	 * Verifica que la conexión continúe activa
	 * @return DBConnection este método se puede encadenar
	 * @throws BadConnectionException
	 */
	public function checkConnection();
	
	/**
	 * Devuelve el recurso de conexión actual
	 * @return resource
	 */
	public function getConnection();
	
	/**
	 * Devuelve el manejador de base de datos
	 * @return DBManager
	 */
	public function getManager();
}

/**
 * @brief Interface para el manejo de operaciones en la base de datos
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup Database
 */

interface DBManager
{
	/**
	 * Inicializa el manejador de la conexión
	 * @param DBConnection $connection
	 * @return void
	 */
	public function __construct($connection);
	
	/**
	 * Envía una consulta a la base de datos
	 * @param string $query
	 * @param int $limit
	 * @param int $offset
	 * @return DBResult
	 * @throws QueryException
	 */
	public function query($query, $limit = 0, $offset = 0);
	
	/**
	 * Envía una consulta múltiple a la base de datos
	 * @param string $multiquery
	 * @return array
	 * @throws QueryException
	 */
	public function multiquery($multiquery);
	
	/**
	 * Inicializa una transacción
	 * @return DBManager este método se puede encadenar
	 */
	public function startTran();
	
	/**
	 * Confirma una transacción
	 * @return DBManager este método se puede encadenar
	 */
	public function commit();
	
	/**
	 * Descarta los cambios de una transacción
	 * @return DBManager este método se puede encadenar
	 */
	public function rollback();
	
	/**
	 * Devuelve el historial de consultas
	 * @return string
	 */
	public function showQueryHistory();
	
	/**
	 * Devuelve el id autoincrementado del último registro insertado
	 * @return int
	 */
	public function insertId();
	
	/**
	 * Evalúa un valor para que pueda ser ingresado en una consulta
	 * @param string $value
	 * @return string
	 * @throws BadConnectionException
	 */
	public function evalSQL($value);
	
	/**
	 * Devuelve un registro de resultado vacío
	 * @return DBResult
	 */
	public function getEmptyResult();
	
	/**
	 * Devuelve un objeto de consulta que puede ser personalizado
	 * @param string $query
	 * @param array $params
	 * @return DBQuery
	 */
	public function getQuery($query, $params = array());
	
	/**
	 * Devuelve un objeto para crear una sentencia preparada
	 * @param string $query
	 * @return DBStatement
	 */
	public function getStatement($query);
	
	/**
	 * Devuelve un modelo inicializado con la configuración proporcionada
	 * @param array $config
	 * @return Model
	 */
	public function getModel($config = array());
}

/**
 * @interface Iterator
 * @brief Iterfaz por defecto de PHP
 * @see http://php.net/manual/es/class.iterator.php
 */

/**
 * @brief Interface para el manejo de resultados de la base de datos.
 * 
 * Es derivada de la interfaz Iterator de PHP
 * @see http://php.net/manual/es/class.iterator.php
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup Database
 */

interface DBResult extends Iterator
{
	/**
	 * Devuelve un registro específico del set de resultados
	 * @param string $type
	 * @return object|array
	 * @throws DatabaseException
	 * @throws EmptyResultException
	 */
	public function fetch($type = 'object');
	
	/**
	 * Devuelve un campo específico en el registro seleccionado
	 * @param string $field
	 * @param int $row
	 * @return string
	 * @throws DatabaseException
	 * @throws EmptyResultException
	 */
	public function getResult($field, $row = 0);
	
	/**
	 * Devuelve el número de registros del resultado
	 * @return int
	 * @throws EmptyResultException
	 */
	public function numRows();
}

/**
 * @brief Interface para el manejo de consultas en la base de datos
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup Database
 */

interface DBQuery
{
	/**
	 * Inicializa una consulta personalizable
	 * @param DBManager $manager
	 * @param string $query
	 * @param array $params
	 * @param int $limit
	 * @param int $offset
	 * @return void
	 */
	public function __construct($manager, $query = '', $params = array(), $limit = 0, $offset = 0);
	
	/**
	 * Asigna la query que será ejecutada
	 * @param string $query
	 * @return DBQuery este método se puede encadenar
	 */
	public function setQuery($query);
	
	/**
	 * Configura los límites de la consulta
	 * @param int $limit
	 * @param int $offset
	 * @return DBQuery este método se puede encadenar
	 */
	public function setLimit($limit, $offset = 0);
	
	/**
	 * Agrega un parámetro a instertarse dentro de la consulta
	 * @param string $param
	 * @return DBQuery este método se puede encadenar
	 */
	public function addParam($param);
	
	/**
	 * Agrega múltiples parámetros que pueden ser insertados dentro de la consulta
	 * @param array $params
	 * @param boolean $replace controla si se borran los parámetros ya existentes
	 * @return DBQuery este método se puede encadenar
	 */
	public function addParams($params, $replace = false);
	
	/**
	 * Ejecuta la consulta sin esperar un resultado
	 * @return DBQuery este método se puede encadenar
	 * @throws QueryException
	 */
	public function exec();
	
	/**
	 * Ejecuta la consulta y devuelve un resultado
	 * @return DBResult
	 * @throws QueryException
	 */
	public function getResult();
	
	/**
	 * Limpia el resultado de la consulta
	 * @return DBQuery este método se puede encadenar
	 */
	public function clearResult();
}

/**
 * @brief Interface para el manejo de sentencias preparadas en la base de datos
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup Database
 */

interface DBStatement
{
	/**
	 * Inicializa la sentencia preparada
	 * @param DBManager $manager
	 * @param string $query
	 * @return void
	 */
	public function __construct($manager, $query = '');
	
	/**
	 * Prepara una consulta como sentencia preparada
	 * @param string $query
	 * @return DBStatement este método se puede encadenar
	 */
	public function prepareQuery($query);
	
	/**
	 * Enlaza un parámetro a la sentencia
	 * @param string $type
	 * @param mixed $param parámetro por referencia
	 * @return DBStatement este método se puede encadenar
	 * @throws StatementException
	 */
	public function bindParam($type, &$param);
	
	/**
	 * Ejecuta una sentencia preparada sin esperar un resultado
	 * @return DBStatement este método se puede encadenar
	 * @throws StatementException
	 */
	public function exec();

	/**
	 * Ejecuta una sentencia preparada y devuelve un resultado
	 * @return DBResult
	 * @throws StatementException
	 */
	public function getResult();
}

// Fin de archivo
