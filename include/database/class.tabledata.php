<?php

/**
 * Clase básica para almacenar datos de una tabla
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @date Sep 2012
 * @version 3
 * @ingroup Database
 */

class TableData extends BasicTable 
{
	protected $manager;

	protected $primary = 'id';

	protected $relations = array();
	
	public function __construct($manager, $config) {
		$this->manager = $manager;
		$this->setConfig($config);
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
					
					$subtable = new ForeignTable($rels[0], $field, $rels[1]);
					$this->relations[$rels[0]] = $subtable;
					// Las llaves foráneas son campos después de todo
					$this->fields[] = $field;
					assert('isset($this->relations[$rels[0]]) && $this->relations[$rels[0]] instanceof ForeignTable');
				}
			}
		}
		
		return $this;
	}
	
	public function setJoins($joins) {
		if(is_array($joins)) {
			foreach($joins as $table_name => $table) {
				if(isset($this->relations[$table_name]) && is_array($table)) {
					$this->relations[$table_name]->setFields($table);
				}
			}
		}
		
		return $this;
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
		if(isset($config['joins'])) {
			$this->setJoins($config['joins']);
		}
		
		return $this;
	}

	protected function getFieldsAndId()
	{
		// Deshabilitemos por ahora el sufijo de la tabla
		// $fields = '`'.$this->table.'`.`id_'.$this->table.'`';
		
		// aplicado DRY a la lista de campos
		$fields = SC.$this->table.SC.'.'.SC.$this->getPrimary().SC;
		
		$other = $this->getFields();
		if($other != '') {
			$fields .= ', '.$other;
		}

		return $fields;
	}
	
	protected function getFields($values = array()) {
		$fields = parent::getFields($values);
		
		if(empty($values)) {
			foreach($this->relations as $jointable) {
				$joinfields = $jointable->getJoinFields($this->fields);
				if($fields == '') {
					$fields = $joinfields;
				}
				elseif($joinfields != '') {
					$fields .= ', '.$joinfields;
				}
			}
		}
		
		return $fields;
	}
	
	protected function getJoins() {
		$sql = '';
		// Se hace join a las tablas
		foreach($this->relations as $jointable) {
			if($jointable->isJoined()) {
				$ftable = $jointable->getTable();
				$sql .= ' LEFT JOIN '.SC.$ftable.SC.' ON '.SC.$this->table.SC.'.'.SC.$jointable->getField().SC.' = '.SC.$ftable.SC.'.'.SC.$jointable->getKey().SC;
			}
		}
		
		return $sql;
	}
}
