<?php

assert("defined('MOONDRAGON_PATH')");

abstract class Manager {
    public abstract function index();

    public function run() {
        $this->index();
    }
}
