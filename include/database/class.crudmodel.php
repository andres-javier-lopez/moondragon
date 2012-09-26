<?php

/**
 * Modulo para manejo automático de operaciones CRUD para una tabla en la base de datos
 * 
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright TuApp.net - GNU Lesser General Public License
 * @date May 2012
 * @version 1.0
 * @ingroup AdvData
 */
abstract class CrudModel extends AdvModel
{
	/**
	 * Nombre de la tabla
	 * @var string $table
	 */
	protected $table;
	
	/**
	 * Array con los nombres de los campos de la tabla
	 * @var array $columns
	 */
	protected $columns;
	
	/**
	 * Array con el nombre de las llaves foráneas en la tabla
	 * @var array $foreign
	 */
	protected $foreign;
	
	/**
	 * Array con especificaciones para hacer join en las consultas
	 * @var array $joins
	 */
	private $joins;
	
	/**
	 * Array con los nombres de las columnas que se agregaron por join
	 * @var array $join_columns
	 */
	private $join_columns;
	
	/**
	 * Constructor de la clase, redefinir para configurar la tabla que sera utilizada
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->table = '';
		$this->columns = array();
		$this->foreign = array();
		$this->joins = array();
		$this->join_columns = array();
	}
	
	/**
	 * Devuelve una cadena formada por los campos de la tabla y su id, usado para selects
	 * @return string
	 */
	protected function getFieldsAndId()
	{
		$fields = '`'.$this->table.'`.`id_'.$this->table.'`';
		
		foreach($this->foreign as $foreign)
		{
			$fields .= ', `'.$this->table.'`.`id_'.$foreign.'`';
		}
		
		foreach($this->columns as $column)
		{
			$fields .= ', `'.$this->table.'`.`'.$column.'_'.$this->table.'`';
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
		$columns = array();
		
		foreach($this->foreign as $foreign)
		{
			if(empty($values))
			{
				$columns[] = '`'.$this->table.'`.`id_'.$foreign.'`';
			}
			elseif(!is_null($values['id_'.$foreign]))
			{
				$columns[] = '`id_'.$foreign.'`';
			}			
		}
		
		foreach($this->columns as $column)
		{
			if(empty($values))
			{
				$columns[] = '`'.$this->table.'`.`'.$column.'_'.$this->table.'`';
			}
			elseif(!is_null($values[$column.'_'.$this->table]))
			{
				$columns[] = '`'.$column.'_'.$this->table.'`';
			}
		}
		
		$string = implode(', ', $columns);
		return $string;
	}
	
	/**
	 * Devuelve los campos utilizados para join, usado para select
	 * @return string
	 */
	protected function getJoinFields()
	{
		$string = '';
		
		foreach($this->joins as $join)
		{
			foreach($join->fields as $alias => $column)
			{
				if(is_string($alias))
				{
					$alias_str = ' AS `'.$alias.'` ';
				}
				else
				{
					$alias_str = '';
				}
				$string .= ', `'.$join->table.'`.`'.$column.'`'.$alias_str;
			}
		}
		
		return $string;
	}
	
	/**
	 * Devuelve cadena de configuración de join para select
	 * @return string
	 */
	protected function getJoins()
	{
		$string = '';
		
		foreach($this->joins as $join)
		{
			$string .= ' '.$join->type.' JOIN '.$join->table;
			if($join->conditions != '')
			{
				$string .= ' ON ('.$join->conditions.') ';
			}
		}
		
		return $string;
	}
	
	/**
	 * Devuelve identificacdor principal de la tabla
	 * @return string;
	 */
	protected function getId()
	{
		return 'id_'.$this->table;
	}
	
	/**
	 * Construye un dataset a partir del resultado de una consulta
	 * @param object $data
	 * @return Datasetobj
	 */
	protected function generateDataset($data)
	{
		return new Datasetobj($this->table, $this->foreign, $this->columns, $this->join_columns, $data);
	}
	
	/**
	 * Función para configurar un nuevo join dentro de la tabla
	 * @param string $table tabla con la que se hará el join
	 * @param array $fields arreglo de campos que serán obtenidos del join
	 * @param string $type tipo de join que será realizado
	 * @param string $conditions condiciones bajo las que se realizará el join
	 * @return void
	 */
	protected function addJoin($table, $fields, $type = 'NATURAL', $conditions = '')
	{
		$join = new StdClass();
		$join->table = $table;
		$join->fields = $fields;
		$join->type = $type;
		$join->conditions = $conditions;
		$this->joins[] = $join;
		
		foreach($join->fields as $alias => $field)
		{
			if(is_string($alias))
			{
				$this->join_columns[] = $alias;
			}
			else
			{
				$this->join_columns[] = $field;
			}
		}
	}
	
	/**
	 * Devuelve el arreglo con la lista de campos de la tabla
	 * @return array
	 */
	public function getFieldNames()
	{
		return $this->columns;
	}
	
	/**
	 * Devuelve el arreglo con la lista de llaves foráneas de la tabla
	 * @return array
	 */
	public function getForeignTables()
	{
		return $this->foreign;
	}
	
	/**
	 * Devuelve un dataset vacío utilizado para hacer inserciones
	 * @return Datasetobj
	 */
	public function getDataset()
	{
		return new Datasetobj($this->table, $this->foreign, $this->columns);
	}
	
	/**
	 * Obtiene el total de registros de la tabla
	 * @param string $where opcionalmente obtiene el total de registros que cumplan con una condicion
	 * @return int
	 * @throws DBException
	 */
	public function countAll($where = '')
	{
		$sql = 'SELECT COUNT(*) as `cuenta` FROM `'.$this->table.'` '.$where.';';
		
		return $this->getVar($sql, 'cuenta');
	}
	
	/**
	 * Lista los registros en la tabla
	 * @param int $page
	 * @param int $xpageunknown_type
	 * @param boolean $inv
	 * @return array
	 * @throws DBException
	 */
	public function listAll($page = 1, $xpage = 0, $inv = true)
	{
		$sql = 'SELECT '.$this->getFieldsAndId().' '.$this->getJoinFields().' FROM '.$this->table.' '.$this->getJoins().' '.$this->getLimit($page, $xpage, $this->countAll());
		if($inv == true)
		{
			$sql .= ' ORDER BY '.$this->getId().' DESC';
		}
		$result = $this->getRows($sql);
		
		$rows = array();
		foreach($result as $data)
		{
			$rows[] = $this->generateDataset($data);
		}
		return $rows;
	}
	
	/**
	 * Lista los registros de la tabla que cumplen con la condición
	 * @param string|array $where
	 * @param int $page
	 * @param int $xpage
	 * @param boolean $inv
	 * @return array
	 * @throws DBException
	 */
	public function listAllWhere($where, $page = 1, $xpage = 0, $inv = true)
	{
		$sql = 'SELECT '.$this->getFieldsAndId().' '.$this->getJoinFields().' FROM '.$this->table.' '.$this->getJoins();
		$sql_where .= ' WHERE ';
		if(is_array($where))
		{
			$and = '';
			foreach($where as $field => $cond)
			{
				$sql_where .= $and.'`'.$field.'` = '.$cond;
				$and = ' AND ';
			}
		}
		elseif (is_string($where))
		{
			$sql_where .= $where;
		}
		else
		{
			throw new AdvModelException('la condición de la consulta es incorrecta');
		}
		$sql .= $sql_where;
		if($inv == true)
		{
			$sql .= ' ORDER BY '.$this->getId().' DESC ';
		}
		$sql .= ' '.$this->getLimit($page, $xpage, $this->countAll($sql_where));
		$result = $this->getRows($sql);
		
		$rows = array();
		foreach($result as $data)
		{
			$rows[] = $this->generateDataset($data);
		}
		return $rows;
	}
	
	/**
	 * Devuelve un solo registro de la tabla con el id especificado
	 * @param int $id
	 * @return Datasetobj
	 * @throws DBException
	 */
	public function get($id)
	{
		$sql = 'SELECT '.$this->getFields().' '.$this->getJoinFields().' FROM '.$this->table.' '.$this->getJoins();
		$sql .= ' WHERE `'.$this->table.'`.`'.$this->getId().'` = "'.$id.'"';

		$data = $this->getRow($sql);
		$dataset = $this->generateDataset($data);
		$dataset->setId($id);
		return $dataset;
	}
	
	/**
	 * Inserta un nuevo registro a partir del dataset proporcionado
	 * @param Datasetobj $new
	 * @return int
	 * @throws DBException
	 */
	public function create(Datasetobj $new)
	{
		$sql = 'INSERT INTO `'.$this->table.'` ('.$this->getFields($new->getColValues()).') ';
		$sql .= 'VALUES('.$new->getColValuesString().')';
		
		$this->exec($sql);
		
		$id = $this->db->insertId();
		return $id;
	}
	
	/**
	 * Actualiza el registro en la tabla de acuerdo a los datos proporcionados en el dataset
	 * @param Datasetobj $obj
	 * @return void
	 * @throws DBException
	 */
	public function update(Datasetobj $obj)
	{
		$sql = "UPDATE `$this->table` ";
		$data = $obj->getColValues();
		
		$sep = 'SET';
		
		foreach($data as $col => $value)
		{
			if(!is_null($value))
			{
				$sql .= "$sep `$col` = '$value' ";
				$sep = ',';
			}
		}
		
		$sql .= 'WHERE `'.$this->table.'`.`'.$this->getId().'` = "'.$obj->getId().'"';
		
		$this->exec($sql);
	}
	
	/**
	 * Elimina el registro con el id proporcionado en la tabla
	 * @param int $id
	 * @return void
	 * @throws DBException
	 */
	public function delete($id)
	{
		$id = $this->db->evalSQL($id);
		$sql = 'DELETE FROM `'.$this->table.'` WHERE `'.$this->getId().'` = "'.$id.'"';
		$this->exec($sql);
	}
}

//Fin de archivo
