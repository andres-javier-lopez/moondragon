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
	
	public function __construct($manager, $config) {
		$this->manager = $manager;
		$this->setConfig($config);
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
						
					$subtable = new ForeignTable($field, $rels[1]);
					$this->relations[$rels[0]] = $subtable;
					// Las llaves foráneas son campos después de todo
					$this->fields[] = $field;
					assert('isset($this->relations[$rels[0]]) && $this->relations[$rels[0]] instanceof ForeignTable');
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
		
		// aplicado DRY a la lista de campos
		$fields = '`'.$this->table.'`.`'.$this->getPrimary().'`, '.$this->getFields();

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
