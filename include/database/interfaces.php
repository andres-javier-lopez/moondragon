<?php

Interface DBConnection
{
	public function __construct($host, $user, $password, $database);
	
	public function checkConnection();
	
	public function getConnection();
	
	public function getManager();
}

Interface DBManager
{
	public function __construct($connection);
	
	public function query($query);
	
	public function multiquery($multiquery);
	
	public function startTran();
	
	public function commit();
	
	public function rollback();
	
	public function showQueryHistory();
	
	public function insertId();
	
	public function evalSQL($value);
	
	public function getEmptyResult();
	
	public function getQuery($query, $params);
}

Interface DBResult extends Iterator
{
	public function fetch($type);
	
	public function getResult($field, $row = 0);
	
	public function numRows();
}

Interface DBQuery
{
	public function __construct($manager, $query, $params);
	
	public function setQuery($query);
	
	public function addParam($param);
	
	public function addParams($params, $replace);
	
	public function exec();
	
	public function getResult();
	
	public function clearResult();
}
