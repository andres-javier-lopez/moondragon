<?php

require_once '../moondragon.manager.php';
header('Content-Type: text/html; charset=utf-8');
class Home extends Manager
{
    public function index() {
        echo _('¡Hola Mundo!');
    }
    
    public function hola() {
    	echo 'definiendo tarea hola';
    }
    
    public function redireccion() {
    	$this->doTask('hola');
    }
}

MoonDragon::run(new Home('moondragon4.0/test/test_manager.php'));

