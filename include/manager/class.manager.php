<?php

abstract class Manager implements Runnable{
	protected $call = array();
	
    public abstract function index();

    public function run() {
    	$task = $this->getTask();
        $this->$task();
    }
    
    public function __call($method, $params) {
    	throw new TaskException();
    }
    
    protected function getTask() {
    	if(isset($_SERVER['PATH_INFO'])) {
    		$pathinfo = trim($_SERVER['PATH_INFO'], '/');
    		if($pathinfo != '') {
    			$this->call = explode('/', $pathinfo);
    			assert('count($this->call) > 0');
    			return $this->call[0];
    		}    		
    	}
    	
    	return 'index';
    }
    
}
