<?php

/**
 * Clase para relacionar llaves foráneas
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup Database
 */

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
	
	public function getJoinFields($fields) {
		assert('is_array($this->fields)');
		if(!empty($this->fields)) {
			if(is_array($fields)) {
				foreach($this->fields as $field) {
					if(!array_key_exists($field, $this->alias) && in_array($field, $fields)) {
						$this->addAlias($field, $this->table.'_'.$field);
					}
				}
			}			
			return $this->getFields();
		}
		else {
			return '';
		}
	}
}
