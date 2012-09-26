<?php

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
	}
	
	public function addParam($param) {
		$this->params[] = $param;
		$this->clearResult();
	}
	
	public function addParams($params, $replace = false) {
		if($replace === true || empty($this->params)) {
			$this->params = $params;
		}
		else {
			$this->params = array_merge($this->params, $params);
		}
		$this->clearResult();
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
	}
	
	public function getResult() {
		if($this->result === false) {
			$this->exec();
		}
		
		return $this->result;
	}
	
	public function clearResult() {
		$this->result = false;
	}
}
