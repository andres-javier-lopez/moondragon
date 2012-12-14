<?php

require_once '../moondragon.manager.php';
header('Content-Type: text/html; charset=utf-8');
class Home extends Manager
{
    /**
     * inicializamos un test de mensaje
     * @return  string
     * 
     */
    public function index() {
        echo _('¡Hola Mundo!');
    }
    
    /**
     * 
     * function que manda para metro
     * @return string
     * 
     */
    public function param() {
    	$prueba = Request::tryGetGET('prueba', 'vacia');
    	echo 'La variable es '.$prueba;
    }
    
    /**
     * testeo de un mensaje 
     * @return string
     * 
     */
    public function hola() {
    	echo 'definiendo tarea hola';
    }
    
    /**
     * re direciona
     * @return string
     * 
     */
    public function redireccion() {
    	$this->doTask('hola');
    }
}

MoonDragon::run(new Home('moondragon4.0/test/test_manager.php'));

