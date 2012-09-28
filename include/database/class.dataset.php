<?php

/**
 * Clase para guardar los datos de un registro en una tabla
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @date Sep 2012
 * @version 1
 * @ingroup Database
 */

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
		return array_map(array($this->manager, 'evalSQL'), $this->data);
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
