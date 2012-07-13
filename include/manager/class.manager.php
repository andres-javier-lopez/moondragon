<?php

abstract class Manager implements Runnable{
	
	protected $call = array();
	
	protected $match_url = '';
	
	public function __construct($match_url = '') {
		$this->match_url = '/'.str_replace('/', '\/', $match_url).'(\/.*)?/i';
	}
	
    public abstract function index();

    public function run() {
    	$task = $this->getTask();
        $this->$task();
    }
    
    public function __call($method, $params) {
    	throw new TaskException();
    }
    
    protected function getTask() {
    	$task = '';
    	
    	if(defined('CLEAN_URL') && CLEAN_URL == true) {
    		assert('CLEAN_URL');
    		if(isset($_SERVER['REQUEST_URI']) && $this->match_url != '') {
    			$uri = $_SERVER['REQUEST_URI'];
    			if(preg_match($this->match_url, $uri, $matches) == 1){
    				assert('count($matches) > 0');
    				if(isset($matches[1])){
    					$pos = strpos($matches[1], '?');
    					if($pos !== false) {
    						$task = $this->loadParams(substr($matches[1], 0, $pos));
    					}
    					else {
    						$task = $this->loadParams($matches[1]);
    					}
    					assert('$matches[1] == "/" || $task != ""');    					
    				}
    			}
    		}
    	}
    	else if(isset($_SERVER['PATH_INFO'])) {
    		$pathinfo = $_SERVER['PATH_INFO'];
    		$task = $this->loadParams($pathinfo);
    		assert('$pathinfo == "/" || $task != ""');
    	}
    	
    	if($task == '' && isset($_GET['task'])) {
    		$task = $_GET['task'];
    	}
    	
    	if($task != '') {
    		return $task;
    	}
    	else {
    		return 'index';
    	}    	
    }
    
    protected function loadParams($params) {
    	$params = trim($params, '/');
    	if($params != '') {
    		$this->call = explode('/', $params);
    		assert('count($this->call) > 0');
    		return $this->call[0];
    	}
    	else {
    		return '';
    	}
    }
    
}
