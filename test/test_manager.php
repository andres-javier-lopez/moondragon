<?php

require_once '../moondragon.manager.php';

class Home extends Manager
{
    public function index() {
        echo 'Hola Mundo!';
    }
    
    public function hola() {
    	echo 'definiendo tarea hola';
    }
    
    public function redireccion() {
    	$this->doTask('hola');
    }
}

MoonDragon::run(new Home('moondragon4.0/test/test_manager.php'));

