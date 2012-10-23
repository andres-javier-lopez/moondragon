<?php

/**
 * Datos básicos de una tabla
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @date Sep 2012
 * @version 2
 * @ingroup Database
 */

class BasicTable
{
	const SUFIX = '#s';
	
	protected $table = '';
	
	protected $sufix = '';
	
	protected $fields = array();
	
	protected $alias = array();
	
	protected $sufix_fields = array();
	
	public function setTable($table) {
		$this->table = $table;
		if($this->sufix == '') {
			$this->setSufix('_'.$this->table);
		}
	}
	
	public function setFields($fields) {
		//$this->fields = $fields;
		foreach($fields as $alias => $field_raw) {
			if(strpos($field_raw, self::SUFIX) !== false) {
				$field = str_replace(self::SUFIX, '', $field_raw);
				$this->sufix_fields[$field] = $field_raw;
			}
			else {
				$field = $field_raw;
			}
			$this->fields[] = $field;
			
			if(is_string($alias)) {
				$this->addAlias($field, $alias);
			}
		}
	}
	
	public function setSufix($sufix) {
		$this->sufix = $sufix;
	}
	
	public function addAlias($field, $alias) {
		$this->alias[$field] = $alias;
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
				
				// Incluido alias
				if(array_key_exists($field, $this->alias)) {
					$alias = ' AS '.SC.$this->alias[$field].SC;
				}
				else {
					$alias = ' AS '.SC.$field.SC;
				}
				
				$fields[] = SC.$this->table.SC.'.'.SC.$this->_field($field).SC.$alias;
			}
			elseif(isset($values[$field]))
			{
				// Eliminado el sufijo del nombre de la tabla
				$fields[] = SC.$this->_field($field).SC;
			}
		}
	
		$string = implode(', ', $fields);
		return $string;
	}
	
	protected function _field($field) {
		if(array_key_exists($field, $this->sufix_fields)) {
			$field = str_replace(self::SUFIX, $this->sufix, $this->sufix_fields[$field]);
		}
		
		return $field;
	} 
}
