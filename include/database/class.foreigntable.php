<?php

class ForeignTable extends TableData
{
	public function __construct($table = NULL, $key = NULL) {
		if(!is_null($table) && !is_null($key)) {
			$this->setTable($table);
			$this->primary = $key;
		}
	}
}