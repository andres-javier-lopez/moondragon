<?php

/**
 * Clase para manejar los resultados de una base de datos MySQL
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup MySQL
 */

class MySQLResult implements DBResult
{
	protected $result;
	
	protected $position;
	
	protected $current;
	
	protected $valid;
        
        /**
         * 
         * @param type $result
         */
	
	public function __construct($result)
	{
		$this->result = $result;
		$this->position = 0;
		$this->valid = true;
	}
	
        /**
         *  TODO no se para que es 
         */
	public function __destruct()
	{
		if($this->checkResult()) {
			$this->result->free();
		}
	}
	
        /**
         * 
         * @param type $type
         * @return type
         * @throws DatabaseException
         * @throws EmptyResultException
         */
	public function fetch($type = 'object')
	{
		if($this->checkResult()) {
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
		else {
			throw new EmptyResultException();
		}
	}
	
        /**
         * 
         * @param type $field
         * @param type $row
         * @return type
         * @throws DatabaseException
         * @throws EmptyResultException
         */
	public function getResult($field, $row = 0)
	{
		if($this->checkResult()) {
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
		else {
			throw new EmptyResultException();
		}
	}
	
        /**
         * 
         * @return type
         * @throws EmptyResultException
         */
	public function numRows()
	{
		if($this->checkResult()) {
			return $this->result->num_rows;
		}
		else {
			throw new EmptyResultException();
		}
	}
	
        /**
         * TODO no se para que es
         */
	public function rewind()
	{
		if($this->checkResult()) {
			$this->position = 0;
			$this->valid = true;
			$this->result->data_seek(0);
			if(!($this->current = $this->result->fetch_object())) {
				$this->valid = false;
			}
		}
		else {
			$this->valid = false;
		}
	}
        /**
         * 
         * @return type
         */
	
	public function current()
	{
		return $this->current;
	}
	
        /**
         * 
         * @return type
         */
	public function key()
	{
		return $this->position;
	}
	/**
         * TODO no se para que es
         */
	public function next()
	{
		if($this->checkResult()) {
			$this->position++;
			if(!($this->current = $this->result->fetch_object())) {
				$this->valid = false;
			}
		}
	}
	
        /**
         * 
         * @return type
         */
	public function valid()
	{
		return $this->valid;
	}
	
        /**
         * 
         * @return boolean
         */
	protected function checkResult() {
		if(!is_null($this->result)) {
			return true;
		}
		else {
			return false;
		}
	}
}