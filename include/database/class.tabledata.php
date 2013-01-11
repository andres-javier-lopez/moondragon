<?php

/**
 * @brief Clase básica para almacenar datos de una tabla
 *
 * Esta clase se usa internamente por el sistema de modelos
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup Database
 */

class TableData extends BasicTable
{
	/**
	 * Manejador de la conexión
	 * @var DBManager $manager
	 */
	protected $manager;

	/**
	 * Nombre de llave primaria de la tabla
	 * @var string $primary
	 */
	protected $primary = 'id';

	/**
	 * Arreglo con las relaciones con tablas foráneas
	 * @var array $relations
	 */
	protected $relations = array();

	/**
	 * Inicializa los datos de la tabla
	 * @param DBManager $manager
	 * @param array $config
	 */

	public function __construct($manager, $config) {
		$this->manager = $manager;
		$this->setConfig($config);
	}


	/**
	 * Devuelve la llave primaria de la tabla
	 * @todo es necesario evaluar el comportamiento con el sistema de sufijos
	 * @return string
	 *
	 */
	public function getPrimary() {
		// Agregar un proceso especial en caso de que estemos usando sufijos de nombre de tabla
		return $this->primary;
	}

	/**
	 * Comprueba si un campo existe dentro de la tabla
	 * @param string $field
	 * @return boolean
	 *
	 */
	public function hasField($field) {
		if(in_array($field, $this->fields)) {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * Establece las relaciones con las tablas foráneas
	 * @param array $relations
	 * @return TableData Este método se puede encadenar
	 */

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

	/**
	 * Establece los campos de las tablas foráneas que deben de leerse en una cosulta
	 * @param array $joins
	 * @return TableData Este método se puede encadenar
	 */
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

	/**
	 * Inicializa la configuración de la tabla
	 * @param array $config
	 * @return TableData Este método se puede encadenar
	 */
	protected function setConfig($config) {
		if(isset($config['table'])) {
			$this->setTable($config['table']);
		}
		if(isset($config['primary'])) {
			$this->primary = $config['primary'];
		}
		if(isset($config['fields'])) {
			$this->setFields($config['fields']);
		}
		if(isset($config['relations'])) {
			$this->setRelations($config['relations']);
		}
		if(isset($config['joins'])) {
			$this->setJoins($config['joins']);
		}
		if(isset($config['suffix'])) {
			$this->setSufix($config['suffix']);
		}
		elseif(isset($config['table'])) {
			$this->setSufix('_'.$this->table);
		}

		return $this;
	}

	/**
	 * Obtiene la lista de campos más la llave primaria
	 * @return string
	 */
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

	/**
	 * Obtiene la lista de campos en formatos para SELECT o para INSERT
	 * @param array $values Lista de valores que serán insertados
	 * @return string
	 */
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

	/**
	 * Construye los joins de una consulta
	 * @return string
	 */
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

// Fin del archivo
