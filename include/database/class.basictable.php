<?php

/**
 * @brief Datos básicos de una tabla
 *
 * Esta clase se usa internamente por el sistema de modelos
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup Database
 */

class BasicTable
{
	/**
	 * Cadena que identifica el sufijo de una tabla
	 * @var string SUFIX
	 */
	const SUFIX = '#s';

	/**
	 * Nombre de la tabla
	 * @var string $table
	 */
	protected $table = '';

	/**
	 * Sufijo de la tabla
	 * @var string $sufix
	 */
	protected $sufix = '';

	/**
	 * Lista de campos de la tabla
	 * @var array $fields
	 */
	protected $fields = array();

	/**
	 * Lista de alias de la tabla
	 * @var array $alias
	 */
	protected $alias = array();

	/**
	 * Lista de campos procesados con el sufijo de la tabla
	 * @var array $sufix_fields
	 */
	protected $sufix_fields = array();

	/**
	 * Asigna el nombre de la tabla
	 * @param string $table
	 * @return BasicTable este método se puede encadenar
	 */
	public function setTable($table) {
		$this->table = $table;
		if($this->sufix == '') {
			$this->setSufix('_'.$this->table);
		}
		return $this;
	}

	/**
	 * Asigna los campos de la tabla
	 * @param array $fields
	 * @return BasicTable este método se puede encadenar
	 */
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
		return $this;
	}

	/**
	 * Asigna el sufijo de la tabla
	 * @param string $sufix
	 * @return BasicTable este método se puede encadenar
	 */
	public function setSufix($sufix) {
		$this->sufix = $sufix;
		return $this;
	}

	/**
	 * Agrega un alias al campo específicado
	 * @param string $field
	 * @param string $alias
	 * @return BasicTable este método se puede encadenar
	 */
	public function addAlias($field, $alias) {
		$this->alias[$field] = $alias;
		return $this;
	}

	/**
	 * Devuelve el nombre de la tabla
	 * @return string
	 */
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
		if(empty($values))
		{
			foreach($this->fields as $field)
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
		}
		else
		{
			foreach($values as $key => $value) {
				// Eliminado el sufijo del nombre de la tabla
				$fields[] = SC.$this->_field($key).SC;
			}
		}


		$string = implode(', ', $fields);
		return $string;
	}

	/**
	 * Comprueba si existe una versión con sufijo para el campo
	 * @param string $field
	 * @return string
	 */
	protected function _field($field) {
		if(array_key_exists($field, $this->sufix_fields)) {
			$field = str_replace(self::SUFIX, $this->sufix, $this->sufix_fields[$field]);
		}

		return $field;
	}
}

// Fin del archivo
