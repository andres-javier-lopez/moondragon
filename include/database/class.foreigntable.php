<?php

class ForeignTable extends BasicTable
{
	protected $field;
	
	protected $key;
		
	public function __construct($table, $field, $key) {
		$this->table = $table;
		$this->field = $field;
		$this->key = $key;
	}
	
	public function getField() {
		return $this->field;
	}
	
	public function getKey() {
		return $this->key;
	}
	
	public function isJoined() {
		assert('is_array($this->fields)');
		if(empty($this->fields)) {
			return false;
		}
		else {
			return true;
		}
	}
	
	public function getJoinFields() {
		assert('is_array($this->fields)');
		if(!empty($this->fields)) {
			return $this->getFields();
		}
		else {
			return '';
		}
	}
}
