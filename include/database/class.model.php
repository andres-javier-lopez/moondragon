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
		$result = $this->manager->query($sql);
		return $result;
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
}
