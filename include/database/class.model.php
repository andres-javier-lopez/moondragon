<?php

class TableData {
	protected $manager;
	
	protected $table;
	
	protected $fields;
	
	protected $relations;
	
	protected function setConfig($config) {
		$this->table = isset($config['table'])?$config['table']:NULL;
		$this->fields = isset($config['fields'])?$config['fields']:NULL;
		$this->relations = isset($config['relations'])?$config['relations']:NULL;
	}
	
	public function setTable($table) {
		$this->table = $table;
	}
	
	public function setFields($fields) {
		$this->fields = $fields;
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

class Reader extends TableData
{
	protected $order;
	
	public function __construct($manager)
	{
		$this->manager = $manager;
	}
		
	public function setOrder($order) {
		$this->order = $order;
	}
	
	public function getRows() {
		// En primera instancia no utilizamos joins
		// El límite también esta desactivado porque aún no se ha implementado en el driver
		$sql = 'SELECT '.$this->getFieldsAndId().' FROM '.$this->table;
		
		// Verificamos si hay una cláusula de order
		if(isset($this->order))
		{
			$sql .= ' ORDER BY '.$this->order.' ';
		}
		
		// Implementando nuevo sistema
		try {
			$result = $this->manager->query($sql);
		}
		catch(QueryException $e) {
			throw new ReadException($e->getMessage());
		}
		return $result;
	}
}

class Dataset
{
	protected $manager;
	
	protected $data;
	
	public function __construct($manager) {
		$this->manager = $manager;
	}
	
	public function __set($name, $value) {
		$this->data[$name] = $value;
	}
	
	public function __get($name) {
		return isset($this->data[$name])?$this->data[$name]:NULL;
	}
	
	/**
	 * Devuelve un arreglo de pares columna, valor con los datos del dataset, para usos internos del sistema
	 * @return array
	 */
	public function getColValues()
	{
		return $this->data;
		//return array_map(array($this->manager, 'evalSQL'), $this->data);
	}
	
	/**
	 * Devuelve una cadena con los valores de las columnas separados por comas, para usos internos del sistema
	 * @return string
	 */
	public function getColValuesString()
	{
		$values = $this->getColValues();
		foreach($values as $key => $value)
		{
			if(!is_null($value))
			{
				$values[$key] = '"'.$value.'"';
			}
			else
			{
				unset($values[$key]);
			}
		}
		return implode(', ', $values);
	}
}

class Model extends TableData
{	
	public function __construct($manager, $config) {
		$this->manager = $manager;
		$this->setConfig($config);
	}
	
	public function getReader() {
		$reader = new Reader($this->manager);
		$reader->setTable($this->table);
		$reader->setFields($this->fields);

		return $reader;
	}
	
	public function getDataset($values = array()) {
		$dataset = new Dataset($this->manager);
		
		foreach($values as $name => $value) {
			$dataset->$name = $value;
		}
		
		return $dataset;
	}
	
	public function create($dataset) {
		// Para procesar multiinserts, es un poco delicado ver que los dataset contengan los mismos campos
		if(is_array($dataset)) {
			$multiinsert = $dataset;
			$dataset = $multiinsert[0];
		}
		
		if(!($dataset instanceof Dataset)) {
			throw new ModelException(_('No se envió un Dataset válido para inserción'));
		}
		
		$sql = 'INSERT INTO `'.$this->table.'` ('.$this->getFields($dataset->getColValues()).') ';
		$sql .= 'VALUES';
		
		if(isset($multiinsert)) {
			foreach($multiinsert as $dataset) {
				if(!($dataset instanceof Dataset)) {
					throw new ModelException(_('No se envió un Dataset válido para inserción'));
				}
				$sql .= '('.$dataset->getColValuesString().')';
			}
		}
		else {
			$sql .= '('.$dataset->getColValuesString().')';
		}
		
		echo $sql;
		/*$this->exec($sql);
		
		$id = $this->db->insertId();
		return $id;*/
	}
}
