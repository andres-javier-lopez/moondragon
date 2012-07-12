<?php

require_once 'moondragon.core.php';


abstract class Manager {
    public abstract function index();

    public function run() {
        $this->index();
    }
}
