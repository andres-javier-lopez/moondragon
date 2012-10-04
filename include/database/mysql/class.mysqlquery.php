<?php

/**
 * Clase para manejar las consultas en una base de datos MySQL
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @date Sep 2012
 * @version 1
 * @ingroup MySQL
 */

class MySQLQuery implements DBQuery
{
	protected $manager;
	
	protected $query;
	
	protected $params;
	
	protected $result;
	
	public function __construct($manager, $query = '', $params = array()) {
		$this->manager = $manager;
		$this->query = $query;
		$this->params = $params;
		$this->clearResult();
	}
	
	public function setQuery($query) {
		$this->query = $query;
		$this->clearResult();
		// Permite encadenamiento de objetos
		return $this;
	}
	
	public function addParam($param) {
		$this->params[] = $param;
		$this->clearResult();
		// Permite encadenamiento de objetos
		return $this;
	}
	
	public function addParams($params, $replace = false) {
		if($replace === true || empty($this->params)) {
			$this->params = $params;
		}
		else {
			$this->params = array_merge($this->params, $params);
		}
		$this->clearResult();
		// Permite encadenamiento de objetos
		return $this;
	}
	
	public function exec() {
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
		
		$this->result = $this->manager->query($query);
		// Permite encadenamiento de objetos
		return $this;
	}
	
	public function getResult() {
		if($this->result === false) {
			$this->exec();
		}
		
		return $this->result;
	}
	
	public function clearResult() {
		$this->result = false;
		// Permite encadenamiento de objetos
		return $this;
	}
}
