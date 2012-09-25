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
}
