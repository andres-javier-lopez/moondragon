<?php

/**
 * @brief Clase para manejar los resultados de una base de datos MySQL
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup MySQL
 */

class MySQLResult implements DBResult
{
	/**
	 * Recurso con el resultado de la consulta
	 * @var mysqli_result $result
	 */
	protected $result;

	/**
	 * Posición del regitro actual
	 * @var int $position
	 */
	protected $position;

	/**
	 * Registro actual
	 * @var object $current
	 */
	protected $current;

	/**
	 * Verifica si es válido continuar con la iteración
	 * @var boolean $valid
	 */
	protected $valid;

	/**
	 * Inicializa el resultado
	 * @param mysqli_result $result
	 * @return void
	 */

	public function __construct($result)
	{
		$this->result = $result;
		$this->position = 0;
		$this->valid = true;
	}

	/**
	 *  Limpia el resultado al destruir el objeto
	 */
	public function __destruct()
	{
		if($this->checkResult()) {
			$this->result->free();
		}
	}

	/**
	 * Obtiene un registro de la lista de resultados
	 * @param string $type Los tipos válidos son 'object', 'row' y 'assoc'
	 * @return array | object
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
	 * Obtiene un campo del registro específicado
	 * @param string $field
	 * @param int $row
	 * @return string
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
	 * Devuelve el número de registros obtenidos
	 * @return int
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
	 * Regresa al principio de la lista de resultados.
	 * @return void
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
	 * Devuelve el objeto actual
	 * @return object
	 */
	public function current()
	{
		return $this->current;
	}

	/**
	 * Devuelve la posición del objeto actual
	 * @return int
	 */
	public function key()
	{
		return $this->position;
	}
	
	/**
	 * Pasa al siguiente registro de la lista de resultados
	 * @return void
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
	 * Comprueba si el resultado actual es válido
	 * @return boolean
	 */
	public function valid()
	{
		return $this->valid;
	}

	/**
	 * Comprueba si se tiene una lista de resultados válida
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

// Fin del archivo
