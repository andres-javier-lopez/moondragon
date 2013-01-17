<?php

/**
 * @brief Clase para relacionar llaves foráneas
 *
 * Esta clase se usa internamente por el sistema de modelos
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup Database
 */

class ForeignTable extends BasicTable
{
	/**
	 * Campo en la tabla original que referencia a la tabla foránea
	 * @var string $field
	 */
	protected $field;

	/**
	 * Llave primaria de la tabla foránea
	 * @var string $key
	 */
	protected $key;

	/**
	 * Inicializa una relación con una tabla foránea
	 * @param string $table Nombre de la tabla foránea
	 * @param string $field Campo que referencia a la tabla foránea
	 * @param string $key Llave primaria de la tabla foránea
	 * @return void
	 */
	public function __construct($table, $field, $key) {
		$this->table = $table;
		$this->field = $field;
		$this->key = $key;
	}

	/**
	 * Devuelve el nombre del campo que referencia a la tabla foránea
	 * @return string
	 */
	public function getField() {
		return $this->field;
	}

	/**
	 * Devuelve la llave primaria de la tabla foránea
	 * @return string
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * Comprueba si han solicitado campos de la tabla foránea
	 * @return boolean
	 */
	public function isJoined() {
		assert('is_array($this->fields)');
		if(empty($this->fields)) {
			return false;
		}
		else {
			return true;
		}
	}

	/**
	 * Obtiene la lista de campos que se han solicitado de la tabla foránea
	 * @param array $fields Lista de campos de la tabla para evitar colisiones con la tabla foránea
	 * @return string
	 */
	public function getJoinFields($fields) {
		assert('is_array($this->fields)');
		if(!empty($this->fields)) {
			if(is_array($fields)) {
				foreach($this->fields as $field) {
					if(!array_key_exists($field, $this->alias) && in_array($field, $fields)) {
						// Si el campo ya existe en la tabla central, y no existe todavía un alias, se agrega un alias para evitar colisiones
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

// Fin del archivo
