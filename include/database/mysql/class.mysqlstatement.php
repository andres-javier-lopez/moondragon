<?php

/**
 * @brief Clase para manejar las sentencias preparadas de una base de datos MySQL
 *
 * @todo Este proceso todavía es experimental
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup MySQL
 * @see http://php.net/manual/es/class.mysqli-stmt.php
 */

class MySQLStatetement implements DBStatement
{
	/**
	 * Manejador de la conexión con la base de datos
	 * @var MySQLManager $manager
	 */
	protected $manager;

	/**
	 * Objeto que controla la sentencia preparada
	 * @var mysqli_stmt $statement
	 */
	protected $statement;

	/**
	 * Lista de tipos para las variables que se enlazan a la sentencia
	 * @var string $types
	 */
	protected $types;

	/**
	 * Lista de parámetros que serán enlazados a la sentencia
	 * @var array $params
	 */
	protected $params;

	/**
	 * Resultado de la sentencia preparada
	 * @var mysqli_result $result
	 */
	protected $result;

	/**
	 * Comprueba si los parámetros ya fueron enlazados
	 * @var boolean $binded
	 */
	protected $binded;
	
	/**
	 * Inicializa la sentencia preparada
	 * @param MySQLManager $manager
	 * @param string $query
	 * @param mysqli_stmt $statement
	 * @return void
	 */
	public function __construct($manager, $query, $statement) {
		$this->manager = $manager;
		$this->statement = $statement;
		$this->prepareQuery($query);
		$this->types = '';
		$this->params = array();
		$this->binded = true;
	}

	/**
	 * Libera los recursos al destruir el objeto
	 */
	public function __destruct() {
		$this->statement->close();
		if($this->result) {
			$this->result->free();
		}
	}
	
	/**
	 * Prepara una sentencia
	 * @param string $query
	 * @return MySQLStatetement Este método se puede encadenar
	 */
	public function prepareQuery($query) {
		$this->statement->prepare($query);
		return $this;
	}

	/**
	 * Enlaza un parámetro a la sentencia
	 * @param string $type Debe de tener un solo caracter
	 * @param mixed $param Recibido por referencia
	 * @return MySQLStatetement Este método se puede encadenar
	 * @throws StatementException
	 * @see http://php.net/manual/es/mysqli-stmt.bind-param.php
	 */
	public function bindParam($type, &$param) {
		assert('is_string($this->types) && is_array($param)');
		if($this->binded == true && !empty($this->params)) {
			throw new StatementException(_('Los parámetros ya fueron enlazados'));
		}
		if(strlen($type != 1)) {
			throw new StatementException(_('El tipo debe de constar de un solo caracter'));
		}
		$this->binded = false;
		$this->types .= $type;
		$this->params[] = $param;
		return $this;
	}

	/**
	 * Ejecuta la sentencia preparada sin esperar un resultado
	 * @return MySQLStatetement Este método se puede encadenar
	 * @throws StatementException
	 */
	public function exec() {
		if($this->binded) {
			if(!$this->statement->execute()) {
				throw new StatementException(_('Fallo la ejecución del statement'));
			}
			$this->result = $this->statement->get_result();
		}
		else {
			if(count($this->params) != strlen($this->types)) {
				throw new StatementException(_('No coincide la lista de parámetros con la lista de tipos'));
			}
				
			assert('count($this->params) == strlen($this->types)');
				
			$params = array_merge(array($this->types), $this->params);
			if(!call_user_func_array(array($this->statement, 'bind_param'), $this->getArrayRefs($params))) {
				throw new StatementException(_('No se pudieron enlazar los parámetros'));
			}
			else {
				$this->binded = true;
			}
			if(!$this->statement->execute()) {
				throw new StatementException(_('Fallo la ejecución del statement'));
			}
			$this->result = $this->statement->get_result();
		}
		return $this;
	}

	/**
	 * Devuelve el resultado de la sentencia preparada
	 * @return MySQLResult
	 * @throws StatementException
	 */
	public function getResult() {
		$this->exec();
		return new MySQLResult($this->result);
	}

	/**
	 * Proceso que actualiza las variables por referencia de un arreglo
	 * 
	 * @param array $array
	 * @return array
	 */
	protected function getArrayRefs($array) {
		/// @todo Es necesario evaluar el funcionamiento de este proceso
		$refs = array();
		foreach($array as $key => $value) {
			$refs[$key] = &$array[$key];
		}

		return $refs;
	}
}

// Fin del archivo
