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

	protected $fields;

	protected $relations;

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
			$this->relations = $config['relations'];
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

	protected function getFieldsAndId()
	{
		// Deshabilitemos por ahora el sufijo de la tabla
		// $fields = '`'.$this->table.'`.`id_'.$this->table.'`';
		$fields = '`'.$this->table.'`.`id`';

		// Dejamos pendiente la parte de lláves foráneas
		/*foreach($this->foreign as $foreign)
		 {
		$fields .= ', `'.$this->table.'`.`id_'.$foreign.'`';
		}*/

		foreach($this->fields as $field)
		{
			// Deshabilitemos por ahora el sufijo de la tabla
			// $fields .= ', `'.$this->table.'`.`'.$field.'_'.$this->table.'`';
			$fields .= ', `'.$this->table.'`.`'.$field.'`';
		}

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

		foreach($this->fields as $field)
		{
			if(empty($values))
			{
				// Eliminado el sufijo del nombre de la tabla
				$fields[] = '`'.$this->table.'`.`'.$field.'`';
			}
			elseif(!is_null($values[$field]))
			{
				// Eliminado el sufijo del nombre de la tabla
				$fields[] = '`'.$field.'`';
			}
		}

		$string = implode(', ', $fields);
		return $string;
	}
}
