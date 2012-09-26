<?php

class MySQLResult implements DBResult
{
	protected $result;
	
	protected $position;
	
	protected $current;
	
	protected $valid;
	
	public function __construct($result)
	{
		$this->result = $result;
		$this->rewind();
	}
	
	public function __destruct()
	{
		$this->result->free();
	}
	
	public function fetch($type = 'object')
	{
		switch( $type )
		{
			case 'object':
				return $this->result->fetch_object();
			case 'row':
				return $this->result->fetch_row();
			case 'assoc':
				return $this->result->fetch_assoc();
			default:
				throw new DatabaseException(_('Se ha enviado un parametro inválido a la función fetch'));
		}
	}
	
	public function getResult($field, $row = 0)
	{
		if($row < $this->numRows())
		{
			$this->result->data_seek($row);
			$data = $this->result->fetch_assoc();
			$this->result->data_seek(0);
		
			if(isset($data[$field]))
			{
				return $data[$field];
			}
			else
			{
				throw new DatabaseException(_('El campo buscado no se encuentra en los valores devueltos'));
			}
		}
		else
		{
			throw new DatabaseException(_('El resultado buscado excede la cantidad de filas devueltas'));
		}
	}
	
	public function numRows()
	{
		return $this->result->num_rows;
	}
	
	public function rewind()
	{
		$this->position = 0;
		$this->valid = true;
		$this->result->data_seek(0);
		if(!($this->current = $this->result->fetch_object())) {
			$this->valid = false;
		}
	}
	
	public function current()
	{
		return $this->current;
	}
	
	public function key()
	{
		return $this->position;
	}
	
	public function next()
	{
		$this->position++;
		if(!($this->current = $this->result->fetch_object())) {
			$this->valid = false;
		}
	}
	
	public function valid()
	{
		return $this->valid;
	}
}