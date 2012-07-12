<?php

abstract class Manager {
    public abstract function index();

    public function run() {
        $this->index();
    }
}
