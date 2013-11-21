<?php

/**
 * @brief Clase para manejar las operaciones con una base de datos MySQL
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup MySQL
 */

class MySQLManager implements DBManager
{
	/**
	 * Objeto de conexión
	 * @var MySQLConnection $connection
	 */
	protected $connection;

	/**
	 * Historial de consultas a la base de datos
	 * @var string $query_history
	 */
	protected $query_history = '';

	/**
	 * Inicializa la conexión
	 * @param MySQLConnection $connection
	 */
	public function __construct($connection) {
		$this->connection = $connection;
	}

	/**
	 * Realiza una consulta a la base de datos
	 * @param string $query
	 * @param int $limit
	 * @param int $offset
	 * @return MySQLResult | boolean
	 * @throws QueryException
	 */
	public function query($query, $limit = 0, $offset = 0)
	{
		$this->connection->checkConnection();
		$msyqli = $this->connection->getConnection();

		if($limit > 0 && $offset >= 0) {
			$query .= ' LIMIT '.$offset.', '.$limit;
		}

		if( $msyqli->real_query($query) === false )
		{
			$this->query_history .= 'Error: '.$query."\n";
			throw new QueryException( _('Ha ocurrido un error en la query').': '.$this->errorMessage()."\n" );
		}

		$this->query_history .= $query."\n";

		if($msyqli->field_count > 0)
		{
			$result = $msyqli->store_result();
			assert('$result->num_rows <= $limit || $limit == 0 || $limit < 0');

			if($result === false)
			{
				throw new QueryException(_('No se puedo recuperar el resultado').': '.$this->errorMessage()."\n");
			}

			//TODO  El resultado debe de ser un nuevo objeto result
			return new MySQLResult($result);
		}
		else
		{
			return true;
		}
	}

	/**
	 * Ejecuta multiples sentencias SQL
	 * @param string $query
	 * @return array
	 * @throws QueryException
	 */
	public function multiquery( $query )
	{
		$this->connection->checkConnection();
		$msyqli = $this->connection->getConnection();

		// Las queries múltiples no se guardan en el log porque son demasiado grandes
		if( $msyqli->multi_query($query) === false )
		{
			throw new QueryException( _('Ha ocurrido un error en la query múltiple').': '.$this->errorMessage()."\n" );
		}

		$result = array();
		do {
			// Utilizar el nuevo objeto result
			// Evaluar que pasaría cuando la query no devuelve un resultado
			// Considerar un objeto multiresult
			$result[] = new MySQLResult($msyqli->store_result());
		}while($msyqli->next_result());

		if($msyqli->errno)
		{
			throw new QueryException( _('Ha ocurrido un error en la query múltiple').': '.$this->errorMessage()."\n" );
		}

		return $result;
	}

	/**
	 * Inicia la ejecución de una transacción
	 * @return MySQLManager Este método se puede encadenar
	 */
	public function startTran()
	{
		$this->connection->getConnection()->autocommit(false);

		return $this;
	}

	/**
	 * Finaliza la ejecución de una transacción
	 * @return MySQLManager Este método se puede encadenar
	 */
	public function commit()
	{
		$this->connection->getConnection()->commit();
		$this->connection->getConnection()->autocommit(true);

		return $this;
	}

	/**
	 * Revierte una transacción no finalizada
	 * @return MySQLManager Este método se puede encadenar
	 */
	public function rollback()
	{
		$this->connection->getConnection()->rollback();
		$this->connection->getConnection()->autocommit(true);

		return $this;
	}

	/**
	 * Devuelve el historial de consultas realizadas
	 * @return string
	 */
	public function showQueryHistory()
	{
		return $this->query_history;
	}

	/**
	 * Devuelve el id autoincrementado de la ultima fila insertada
	 * @return int
	 */
	public function insertId()
	{
		return $this->connection->getConnection()->insert_id;
	}

	/**
	 * Evalúa los valores a ingresar en las sentencias SQL
	 * @param string $value
	 * @return string
	 */
	public function evalSQL( $value )
	{
		if(is_null($value)) {
			return NULL;
		}

		if ( get_magic_quotes_gpc() )
		{
			stripslashes( $value );
		}

		$this->connection->checkConnection();
		$value = $this->connection->getConnection()->real_escape_string( $value );

		return $value;
	}

	/**
	 * Devuelve un resultado vacío
	 * @return MySQLResult
	 */
	public function getEmptyResult() {
		return new MySQLResult(NULL);
	}

	/**
	 * Devuelve un objeto para consultas personalizadas
	 * @param string $query
	 * @param array $params
	 * @return MySQLQuery
	 */
	public function getQuery($query, $params = array()) {
		return new MySQLQuery($this, $query, $params);
	}

	/**
	 * Devuelve un objeto para realizar una sentencia preparada
	 * @param string $query
	 * @return MySQLStatetement
	 */
	public function getStatement($query) {
		$statement = $this->connection->stmt_init();
		return new MySQLStatetement($this, $query, $statement);
	}

	/**
	 * Devuelve un objeto de modelo con la configuración específicada
	 * @param array $config
	 * @return Model
	 */
	public function getModel($config = array()) {
		return new Model($this, $config);
	}

	/**
	 * Muestra el mensaje de error de mysql
	 * @return string
	 */
	private function errorMessage()
	{
		return $this->connection->getConnection()->error.' ('.$this->connection->getConnection()->errno.')';
	}
}

// Fin del archivo
