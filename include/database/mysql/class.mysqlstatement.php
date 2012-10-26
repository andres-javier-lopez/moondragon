<?php

/**
 * Clase para manejar las sentencias preparadas de una base de datos MySQL
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup MySQL
 */

class MySQLStatetement implements DBStatement
{
	protected $manager;
	
	protected $statement;
	
	protected $types;
	
	protected $params;
	
	protected $result;
	
	protected $binded;
	
	public function __construct($manager, $query, $statement) {
		$this->manager = $manager;
		$this->statement = $statement;
		$this->prepareQuery($query);
		$this->types = '';
		$this->params = array();
		$this->binded = true;
	}
	
	public function __destruct() {
		$this->statement->close();
		if($this->result) {
			$this->result->free();
		}
	}
	
	public function prepareQuery($query) {
		$this->statement->prepare($query);
		return $this;
	}
	
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
	
	public function exec() {
		if($this->binded) {
			$this->statement->execute();
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
	
	public function getResult() {
		$this->exec();
		return new MySQLResult($this->result);
	}
	
	protected function getArrayRefs($array) {
		// Es necesario evaluar el funcionamiento de este proceso
		$refs = array();
		foreach($array as $key => $value) {
			$refs[$key] = &$array[$key];
		}
		
		return $refs;
	}
}