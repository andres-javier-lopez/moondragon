<?php

$ruta = './moondragon';
set_include_path(get_include_path() . PATH_SEPARATOR . $ruta);

include 'moondragon.database.php';
include 'moondragon.manager.php';
include 'moondragon.render.php';

class Home extends Manager
{
    public function index() {
        echo 'Hola Mundo!';
    }
}

$home = new Home();
$home->run();

