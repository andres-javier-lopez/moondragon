<?php

/**
 * @brief Clase para realizar operaciones en la base de datos
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup Database
 */

class Model extends TableData
{
	/**
	 * Valores de configuración de una tabla en el modelo
	 * @var array $config
	 */
	protected $config;

	/**
	 * Inicializa un modelo basado en la configuración proporcionada
	 * @param DBManager $manager
	 * @param array $config
	 * @return void
	 */
	public function __construct($manager, $config) {
		parent::__construct($manager, $config);
		$this->config = $config;
	}

	/**
	 * Devuelve un objeto utilizado para consultas de lectura más personalizadas
	 * @return Reader
	 */
	public function getReader() {
		$reader = new Reader($this->manager, $this->config);
		return $reader;
	}

	/**
	 * Devuelve un dataset vacío o precargado con una lista de valores
	 * @param array $values Lista de valores opcionales para precargar un dataset
	 * @return Dataset
	 */
	public function getDataset($values = array()) {
		$dataset = new Dataset($this->manager);

		foreach($values as $name => $value) {
			if($this->hasField($name)) {
				$dataset->$name = $value;
			}
			elseif ($this->getPrimary() == $name) {
				// TODO Es necesario pensar en un sistema adecuado de warnings
				trigger_error(_('No se puede asignar la llave primaria'), E_USER_WARNING);
			}
			else {
				// aqui tambien
				trigger_error(sprintf(_('No existe el campo %s dentro de la tabla'), $name), E_USER_WARNING);
			}
		}

		return $dataset;
	}

	/**
	 * Inserta un nuevo registro en la base de datos a partir de un dataset
	 * @param Dataset $dataset
	 * @return int Id autoincrementado del registro insertado
	 * @throws ModelException
	 * @throws CreateException
	 */
	public function create($dataset) {
		if(!($dataset instanceof Dataset)) {
			throw new ModelException(_('No se envió un Dataset válido para inserción'));
		}

		$sql = 'INSERT INTO '.SC.$this->table.SC.' ('.$this->getFields($dataset->getColValues()).') ';
		$sql .= 'VALUES ('.$dataset->getColValuesString().')';

		try {
			$this->manager->query($sql);
		}
		catch(QueryException $e) {
			throw new CreateException($e->getMessage());
		}

		$id = $this->manager->insertId();

		return $id;
	}
	
	/**
	 * Devuelve la lista de todos los registros en una tabla
	 * @return DBResult
	 * @throws ReadException
	 */
	public function read() {
		// Este es por si acaso
		return $this->getReader()->getRows();
	}

	/**
	 * Obtiene el id de un elemento único que cumpla ciertas condiciones
	 * 
	 * Puede consultarse una codición única y dejar el campo de valor vacío, enviarse un par campo - valor,
	 * o reemplazar una lista de valores dentro de la condición preformateada
	 * @param string $cond
	 * @param string|array $value
	 * @return int
	 * @throws ModelException
	 * @throws ReadException
	 */
	public function getId($cond, $value = '') {
		if(!is_string($cond)) {
			throw new ModelException(_('La condición debe ser una cadena de texto'));
		}
		assert('is_string($cond)');

		$sql = 'SELECT '.SC.$this->getPrimary().SC.' FROM '.SC.$this->table.SC.' WHERE ';
		if($value != '' && is_string($value)) {
			assert('is_string($value)');
			assert('!is_array($value)');
			$sql .= SC.$cond.SC.' = '.SV.$this->manager->evalSQL($value).SV.';';
		}
		else {
			$sql .= $cond;
		}

		$query = $this->manager->getQuery($sql);
		if(is_array($value)) {
			$query->addParams($value);
		}

		try {
			$result = $query->getResult();
		}
		catch(QueryException $e) {
			throw new ReadException($e->getMessage());
		}

		if($result->numRows() != 1) {
			throw new ReadException(_('La condición de getId debe devolver exactamente un resultado'));
		}
		assert('$result->numRows() == 1');
		$data = $result->fetch();
		assert('$data');

		$id = $this->getPrimary();
		assert('isset($data->$id)');
		return $data->$id;
	}


	/**
	 * Devuelve un solo registro de la tabla con el id especificado
	 * @param int $id
	 * @return Dataset
	 * @throws ReadException
	 */
	public function getData($id)
	{
		// Eliminando los joins por ahora
		// $sql = 'SELECT '.$this->getFields().' '.$this->getJoinFields().' FROM '.$this->table.' '.$this->getJoins();
		$sql = 'SELECT '.$this->getFields().' FROM '.SC.$this->table.SC.' '.$this->getJoins();
		$sql .= ' WHERE '.SC.$this->table.SC.'.'.SC.$this->getPrimary().SC.' = '.SV.'%s'.SV;

		$query = $this->manager->getQuery($sql, array($id));
		try {
			$result = $query->getResult();
		}
		catch(QueryException $e) {
			throw new ReadException($e->getMessage());
		}
		$data = $result->fetch();

		if(is_null($data)) {
			throw new ReadException(_('No se recupero el registro seleccionado'));
		}

		$values = array();
		foreach($this->fields as $field) {
			if(array_key_exists($field, $this->alias)) {
				$alias = $this->alias[$field];
				$values[$field] = $data->$alias;
			}
			else {
				$values[$field] = $data->$field;
			}
		}
		$dataset = $this->getDataset($values);
		// Es mejor no incluir la llave primaria aquí
		// $id_field = $this->getPrimary();
		// $dataset->$id_field = $id;

		return $dataset;
	}

	/**
	 * Actualiza el registro con el id específicado de acuerdo a los datos proporcionados en el dataset
	 * 
	 * Los campos que no estén incluídos en el dataset no son actualizados
	 * @param int $id
	 * @param Dataset $obj
	 * @return void
	 * @throws UpdateException
	 */
	public function update($id, Dataset $dataset)
	{
		$sql = 'UPDATE '.SC.$this->table.SC.' ';
		$data = $dataset->getColValues();

		$sep = 'SET ';

		foreach($data as $col => $value)
		{
			if(!is_null($value))
			{
				$sql .= $sep.SC.$this->_field($col).SC.' = '.SV.$value.SV.' ';
				$sep = ', ';
			}
		}

		$sql .= 'WHERE '.SC.$this->table.SC.'.'.SC.$this->getPrimary().SC.' = '.SV.$this->manager->evalSQL($id).SV;

		try {
			$this->manager->query($sql);
		}
		catch(QueryException $e) {
			throw new UpdateException($e->getMessage());
		}
	}

	/**
	 * Elimina el registro con el id proporcionado en la tabla
	 * @param int $id
	 * @return void
	 * @throws DeleteException
	 */
	public function delete($id)
	{
		$sql = 'DELETE FROM '.SC.$this->table.SC.' WHERE '.SC.$this->getPrimary().SC.' = '.SV.'%s'.SV;

		try {
			$this->manager->getQuery($sql, array($id))->exec();
		}
		catch(QueryException $e) {
			throw new DeleteException($e->getMessage());
		}
	}

	/**
	 * Borra todos los registros que cumplan con la condición específicada
	 * @todo Se deben de ampliar los tipos de condiciones disponibles
	 * @param string $where
	 * @return void
	 * @throws DeleteException
	 */

	public function deleteWhere($where) {
		$sql = 'DELETE FROM '.SC.$this->table.SC.' WHERE ';

		if(is_array($where)) {
			$where_array = array();
			foreach($where as $field => $value) {
				$where_array[] = SC.$this->_field($field).SC.' = '.SV.$this->manager->evalSQL($value).SV;
			}
			$sql .= implode(' AND ', $where_array);
		}
		else {
			$sql .= $where;
		}

		try {
			$this->manager->query($sql);
		}
		catch(QueryException $e) {
			throw new DeleteException($e->getMessage());
		}
	}
}

// Fin de archivo

