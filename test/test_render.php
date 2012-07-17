<?php

include '../moondragon.render.php';

Template::addDir('templates');

$vars['hola'] = _('Hola Mundo!');
$vars['test'] = _('Esta es una prueba');

echo Template::load('templates/page.hola.tpl', $vars);