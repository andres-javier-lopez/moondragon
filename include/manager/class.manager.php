<?php

abstract class Manager implements Runnable{
    public abstract function index();

    public function run() {
        $this->index();
    }
}
