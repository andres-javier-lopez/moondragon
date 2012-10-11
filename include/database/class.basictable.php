<?php

class BasicTable
{
	protected $table;
	
	protected $fields = array();
	
	public function setTable($table) {
		$this->table = $table;
	}
	
	public function setFields($fields) {
		$this->fields = $fields;
	}
	
	public function getTable() {
		return $this->table;
	}
	
	/**
	 * Devuelve una cadena con la lista de campos de la tabla, usado para insert y update
	 * @param array $values contiene los valores válidos para la inserción
	 * @return string
	 */
	protected function getFields($values = array())
	{
		// Esta función se movio para una clase más básica
		$fields = array();
	
		// Las llaves foráneas ya están en la lista de campos
		foreach($this->fields as $field)
		{
			if(empty($values))
			{
				// Eliminado el sufijo del nombre de la tabla
				$fields[] = '`'.$this->table.'`.`'.$field.'`';
			}
			elseif(isset($values[$field]))
			{
				// Eliminado el sufijo del nombre de la tabla
				$fields[] = '`'.$field.'`';
			}
		}
	
		$string = implode(', ', $fields);
		return $string;
	}
}
