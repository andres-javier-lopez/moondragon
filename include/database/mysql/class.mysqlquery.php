<?php

/**
 * Clase para manejar las consultas en una base de datos MySQL
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup MySQL
 */

class MySQLQuery implements DBQuery
{
	protected $manager;
	
	protected $query;
	
	protected $limit;
	
	protected $offset;
	
	protected $params;
	
	protected $result;
	
	public function __construct($manager, $query = '', $params = array(), $limit = 0, $offset = 0) {
		$this->manager = $manager;
		$this->query = $query;
		$this->params = $params;
		$this->limit = $limit;
		$this->offset = $offset;
		$this->clearResult();
	}
	
	public function setQuery($query) {
		$this->query = $query;
		$this->clearResult();

		return $this;
	}
	
	public function setLimit($limit, $offset = 0) {
		$this->limit = $limit;
		$this->offset = $offset;
		
		return $this;
	}
	
	public function addParam($param) {
		$this->params[] = $param;
		$this->clearResult();

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
		
		return $this;
	}
	
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
	
	public function getResult() {
		if($this->result === false) {
			$this->exec();
		}
		
		return $this->result;
	}
	
	public function clearResult() {
		$this->result = false;
		
		return $this;
	}
}
