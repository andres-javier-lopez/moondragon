<?php

/**
 * @brief Clase para manejar las consultas en una base de datos MySQL
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup MySQL
 */

class MySQLQuery implements DBQuery
{
	/**
	 * Manejador de la conexión
	 * @var MySQLManager $manager
	 */
	protected $manager;

	/**
	 * Consulta que se enviará a la base de datos
	 * @var string $query
	 */
	protected $query;

	/**
	 * Límite de registros que devolverá la consulta
	 * @var int $limit
	 */
	protected $limit;

	/**
	 * Registro inicial que devolvera la consulta
	 * @var int $offset
	 */
	protected $offset;

	/**
	 * Parámetros que se insertarán en la consulta
	 * @var array $params
	 */
	protected $params;

	/**
	 * Objeto con el resultado de la consulta
	 * @var MySQLResult $result
	 */
	protected $result;

	/**
	 * Inicialia la consulta a la base de datos
	 * @param MySQLManager $manager
	 * @param string $query
	 * @param array $params
	 * @param int $limit
	 * @param int $offset
	 * @return void
	 */
	public function __construct($manager, $query = '', $params = array(), $limit = 0, $offset = 0) {
		$this->manager = $manager;
		$this->query = $query;
		$this->params = $params;
		$this->limit = $limit;
		$this->offset = $offset;
		$this->clearResult();
	}

	/**
	 * Asigna la consulta que se ejecutará
	 * @param string $query
	 * @return MySQLQuery Este método se puede encadenar
	 */

	public function setQuery($query) {
		$this->query = $query;
		$this->clearResult();

		return $this;
	}
	
	/**
	 * Establece los límites de registros de la consulta
	 * @param int $limit
	 * @param int $offset
	 * @return MySQLQuery Este método se puede encadenar
	 */
	public function setLimit($limit, $offset = 0) {
		$this->limit = $limit;
		$this->offset = $offset;

		return $this;
	}
	
	/**
	 * Agrega un parámetro que se insertará en la consulta
	 * @param string $param
	 * @return MySQLQuery Este método se puede encadenar
	 */
	public function addParam($param) {
		$this->params[] = $param;
		$this->clearResult();

		return $this;
	}
	
	/**
	 * Agrega una colección de parámetros que se insetarán en la consulta
	 * @param array $params
	 * @param boolean $replace
	 * @return MySQLQuery Este método se puede encadenar
	 */
	public function addParams($params, $replace = false) {
		if($replace === true || empty($this->params)) {
			$this->params = $params;
		}
		else {
			$this->params = array_merge($this->params, $params);
		}
		$this->clearResult();

		return $this;
	}
	
	/**
	 * Ejecuta la consulta sin esperar un resultado
	 * @return MySQLQuery Este método se puede encadenar
	 * @throws QueryException
	 */
	public function exec() {
		assert('isset($this->limit) && isset($this->offset)');
		if(!empty($this->params)) {
			$params = array_map(array($this->manager, 'evalSQL'), $this->params);
			$query = vsprintf($this->query, $params);
		}
		else {
			$query = $this->query;
		}

		if($query == '') {
			throw new QueryException(_('La consulta esta vacía'));
		}

		$this->result = $this->manager->query($query, $this->limit, $this->offset);

		return $this;
	}
	
	/**
	 * Ejecuta la consulta y devuelve el resultado
	 * @return MySQLResult
	 * @throws QueryException
	 */
	public function getResult() {
		if($this->result === false) {
			$this->exec();
		}

		return $this->result;
	}
	
	/**
	 * Limpia los resultados de la consulta
	 * @return MySQLQuery Este método se puede encadenar
	 */
	public function clearResult() {
		$this->result = false;
		return $this;
	}
}

// Fin del archivo
