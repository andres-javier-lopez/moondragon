<?php

include '../moondragon.render.php';

$vars['hola'] = _('Hola Mundo!');
$vars['test'] = _('Esta es una prueba');

Template::load('templates/page.hola.tpl', $vars);