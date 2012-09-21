<?php

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
