<?php

/**
 * Clase básica para almacenar datos de una tabla
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @date Sep 2012
 * @version 1
 * @ingroup Database
 */

class TableData {
	protected $manager;

	protected $table;

	protected $primary = 'id';

	protected $fields = array();

	protected $relations = array();
	
	public function __construct($table = NULL, $key = NULL) {
		if(!is_null($table) && !is_null($key)) {
			$this->setTable($table);
			$this->primary = $key;
		}
	}

	public function setTable($table) {
		$this->table = $table;
	}

	public function setFields($fields) {
		$this->fields = $fields;
	}

	public function getPrimary() {
		// Agregar un proceso especial en caso de que estemos usando sufijos de nombre de tabla
		return $this->primary;
	}
	
	public function hasField($field) {
		if(in_array($field, $this->fields)) {
			return true;
		}
		elseif (array_key_exists($field, $this->relations)){
			return true;
		}
		else {
			return false;
		}
	}
	
	public function setRelations($relations) {
		if(is_array($relations)) {
			assert('is_array($relations)');
				
			foreach($relations as $field => $relation) {
				$rels = explode('.', $relation);
				if(count($rels) == 2) {
					assert('isset($rels[0]) && isset($rels[1])');
					if(!is_string($field)) {
						$field = $rels[1];
					}
						
					$subtable = new TableData($rels[0], $rels[1]);
					$this->relations[$field] = $subtable;
					assert('isset($this->relations[$field]) && $this->relations[$field] instanceof TableData');
				}
			}
		}
	}
	
	
	protected function setConfig($config) {
		if(isset($config['table'])) {
			$this->table = $config['table'];
		}
		if(isset($config['primary'])) {
			$this->primary = $config['primary'];
		}
		if(isset($config['fields'])) {
			$this->fields = $config['fields'];
		}
		if(isset($config['relations'])) {
			$this->setRelations($config['relations']);
		}
	}

	protected function getFieldsAndId()
	{
		// Deshabilitemos por ahora el sufijo de la tabla
		// $fields = '`'.$this->table.'`.`id_'.$this->table.'`';
		$fields = '`'.$this->table.'`.`'.$this->getPrimary().'`, '.$this->getFields();

		// Dejamos pendiente la parte de lláves foráneas
		/*foreach($this->foreign as $foreign)
		 {
		$fields .= ', `'.$this->table.'`.`id_'.$foreign.'`';
		}*/

		/*foreach($this->fields as $field)
		{
			// Deshabilitemos por ahora el sufijo de la tabla
			// $fields .= ', `'.$this->table.'`.`'.$field.'_'.$this->table.'`';
			$fields .= ', `'.$this->table.'`.`'.$field.'`';
		}*/

		return $fields;
	}

	/**
	 * Devuelve una cadena con la lista de campos de la tabla, usado para insert y update
	 * @param array $values contiene los valores válidos para la inserción
	 * @return string
	 */
	protected function getFields($values = array())
	{
		$fields = array();

		// Una vez más deshabilitamos las llaves foráneas

		/*foreach($this->foreign as $foreign)
		 {
		if(empty($values))
		{
		$columns[] = '`'.$this->table.'`.`id_'.$foreign.'`';
		}
		elseif(!is_null($values['id_'.$foreign]))
		{
		$columns[] = '`id_'.$foreign.'`';
		}
		}*/
		
		foreach($this->relations as $field => $table)
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
