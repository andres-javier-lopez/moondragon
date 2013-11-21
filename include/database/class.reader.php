<?php

/**
 * @brief Clase para realizar lecturas a la base de datos
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup Database
 */

class Reader extends TableData
{
	/**
	 * Clausula para determinar el orden de los registros
	 * @var string $order
	 */
	protected $order;
	
	/**
	 * Clausula con la condición de lectura en la tabla
	 * @var string $where
	 */
	protected $where;
	
	/**
	 * Lista de variables que son insertadas dentro de la consulta
	 * @var array $vars
	 */
	protected $vars;
	
	/**
	 * Campos incluídos desde las tablas foráneas
	 * @var string $join_fields
	 */
	protected $join_fields = '';
	
	/**
	 * Tablas foráneas incluídas en la consulta
	 * @var string $join_tables
	 */
	protected $join_tables = '';
	
	/**
	 * Límite de registros devueltos por el lector
	 * @var int $limit
	 */
	protected $limit = 0;
	
	/**
	 * Desplazamiento del punto inicial desde el que se obtienen los registros
	 * @var int $offset
	 */
	protected $offset = 0;

        /**
         * Inicializa el lector con la configuración de la tabla
         * @param DBManager $manager
         * @param array $config
         * @return void
         */
	public function __construct($manager, $config)
	{
		parent::__construct($manager, $config);
		$this->where = '';
		$this->vars = array();
		$this->order = '';
	}
	
        /**
         * Permite incluir un join personalizado a la consulta
         * 
         * Este método es para procesos de alto nivel que no se pueden realizar con 
         * el sistema de joins normal
         * @todo es necesario hacer más pruebas de funcionamiento
         * @param string $fields lista de campos de join que se anexan a la consulta
         * @param string $tables lista de tablas  que se anexan a la consulta
         * @return Reader Este método se puede encadenar 
         */
	public function setJoin($fields, $tables) {
		// Experimental No esta del todo probado
		$this->join_fields = ', '.$fields;
		$this->join_tables = $tables;
		
		return $this;
	}

        /**
         * Agrega una cláusula de orden directamente a la consulta
         * @param string $order
         * @return Reader Este método se puede encadenar
         */
	public function setOrder($order) {
		$this->order = $order;
		// Permite encadenamiento de objetos
		return $this;
	}
	
        /**
         * Agrega una cláusula de orden al proceso de lectura
         * 
         * Puede llamarse varias veces para agregar cláusulas adicionales
         * @param string $field
         * @param string $order
         * @return Reader Este método se puede encadenar
         */
	public function orderBy($field, $order = 'ASC') {
		if($this->order == '') {
			$this->order = SC.$this->_field($field).SC.' '.$order;
		}
		else {
			$this->order .= ', '.SC.$this->_field($field).SC.' '.$order;
		}
		
		return $this;
	}
        
        /**
         * Establece el límite de registros y el punto de partida de la consulta
         * @param int $limit Límite de registros devueltos
         * @param int $offset Registro inicial de lectura
         * @return Reader Este método se puede encadenar
         */
	
	public function setLimit($limit, $offset = 0) {
		$this->limit = $limit;
		$this->offset = $offset;
	
		return $this;
	}

        /**
         * Agrega una codición a la consulta
         * @param string $where
         * @param string | array $var
         * @return Reader Este método se puede encadenar
         */
	public function addWhere($where, $var = NULL) {
		/** 
		 * Existen tres maneras de utilizar la función de where:
		 * se puede agregar una condición directamente,
	 	 * se puede agregar una condición y una lista de variables para reemplazar,
		 * y se pueden agregar pares campo/valor que se van adjuntando a la condición.
		 * Por defecto se adjuntan con AND, si se quiere hacer de otra manera
		 * hay que enviar la condición completa.
		 * 
		 * ADVERTENCIA: Evaluar con cuidado el orden de las variables en el arreglo
		 * podrían tener consecuencias inesperadas si se llega a perder el orden
		 * 
		 */
				
		if(is_null($var)) {
			$string = $where;
		} elseif(is_array($var)) {
			$string = $where;
			$this->vars = array_merge($this->vars, $var);
		} else {
			$string = SC.$this->_field($where).SC.' = '.SV.'%s'.SV;
			$this->vars[] = $var;
		}
		
		if($this->where == '') {
			$this->where = ' WHERE '.$string;
		} else {
			$this->where .= ' AND '.$string;
		}
		// Permite encadenamiento de objetos
		return $this;
	}

	/**
	 * Devuelve la lista de registros de la consulta
	 * @return DBResult
	 * @throws ReadException
	 */
	public function getRows() {
		// En primera instancia no utilizamos joins
		// El límite también esta desactivado porque aún no se ha implementado en el driver
		$sql = 'SELECT '.$this->getFieldsAndId().' '.$this->join_fields.' FROM '.SC.$this->table.SC;
		
		$sql .= $this->getJoins().' '.$this->join_tables;
		
		// Implementando sistema de where
		if($this->where != '') {
			$sql .= $this->where;
		}

		// Verificamos si hay una cláusula de order
		if($this->order != '')
		{
			$sql .= ' ORDER BY '.$this->order.' ';
		}

		// Implementando nuevo sistema
		try {
			// Evaluamos si se agregaron variables a la consulta			
			if(!empty($this->vars)) {
				$result = $this->manager->getQuery($sql)->setLimit($this->limit, $this->offset)->addParams($this->vars)->getResult();
			}
			else {
				$result = $this->manager->query($sql, $this->limit, $this->offset);
			}
		}
		catch(QueryException $e) {
			throw new ReadException($e->getMessage());
		}
		return $result;
	}
}

// Fin del archivo
