<?php

/**
 * Clase para realizar lecturas a la base de datos
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup Database
 */

class Reader extends TableData
{
	protected $order;
	
	protected $where;
	
	protected $vars;
	
	protected $join_fields = '';
	
	protected $join_tables = '';
	
	protected $limit = 0;
	
	protected $offset = 0;

        /**
         * 
         * @param type $manager
         * @param type $config
         */
	public function __construct($manager, $config)
	{
		parent::__construct($manager, $config);
		$this->where = '';
		$this->vars = array();
		$this->order = '';
	}
	
        /**
         * 
         * @param type $fields
         * @param type $tables
         * @return \Reader
         * 
         */
	public function setJoin($fields, $tables) {
		// No esta del todo probado
		$this->join_fields = ', '.$fields;
		$this->join_tables = $tables;
		
		return $this;
	}

        /**
         * 
         * @param type $order
         * @return \Reader
         */
	public function setOrder($order) {
		$this->order = $order;
		// Permite encadenamiento de objetos
		return $this;
	}
	
        /**
         * 
         * @param type $field
         * @param type $order
         * @return \Reader
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
         * 
         * @param type $limit
         * @param type $offset
         * @return \Reader
         */
	
	public function setLimit($limit, $offset = 0) {
		$this->limit = $limit;
		$this->offset = $offset;
	
		return $this;
	}

        /**
         * 
         * @param type $where
         * @param type $var
         * @return \Reader
         */
	public function addWhere($where, $var = NULL) {
		// Existen tres maneras de utilizar la función de where:
		// se puede agregar una condición directamente,
		// se puede agregar una condición y una lista de variables para reemplazar,
		// y se pueden agregar pares campo/valor que se van adjuntando a la condición.
		// Por defecto se adjuntan con AND, si se quiere hacer de otra manera
		// hay que enviar la condición completa.
		
		// ADVERTENCIA: Evaluar con cuidado el orden de las variables en el arreglo
		// podrían tener consecuencias inesperadas si se llega a perder el orden
		
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
