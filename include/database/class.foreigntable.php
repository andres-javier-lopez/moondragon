<?php

class ForeignTable
{
	protected $field;
	
	protected $key;
	
	public function __construct($field, $key) {
		$this->field = $field;
		$this->key = $key;
	}
	
	public function getField() {
		return $this->field;
	}
	
	public function getKey() {
		return $this->key;
	}
}
