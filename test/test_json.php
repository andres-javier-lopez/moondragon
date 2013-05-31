<?php

require_once '../moondragon.manager.php';
class JSONM extends JSONManager
{
    /**
     * devolvemos un resultado exitoso
     * @return  array
     */
    public function index() {
        return $this->isSuccess();
    }
    
	/**
     * devolvemos un resultado con datos
     * @return  array
     */
	public function data() {
		$data = array("uno" => 1, "dos" => 2);
		return $this->isData($data);
	} 
	
	/**
     * devolvemos un fallo
     * @return  array
     */
	public function fail() {
		return $this->isFailure();
	}
	
	/**
     * devolvemos un mensaje de error
     * @return  array
     */
	public function error() {
		return $this->isError('Esto es un error');
	}
}

MoonDragon::run(new JSONM('moondragon/test/test_json.php'));

