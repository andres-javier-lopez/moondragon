<?php

require_once '../moondragon.manager.php';
header('Content-Type: text/html; charset=utf-8');

Router::addSection('test', 'extra_manager.php', 'Home', true);
Router::enroute();