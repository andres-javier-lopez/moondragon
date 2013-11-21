<?php

/**
 * @brief Clase para guardar los datos de un registro en una tabla
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup Database
 */

class Dataset
{
	/**
	 * Instancia del manejador de la conexión con la base de datos
	 * @var DBManager $manager
	 */
	protected $manager;

	/**
	 * Arreglo con los datos pertenecientes a un registro en la tabla
	 * @var array $data
	 */
	protected $data;

	/**
	 * Inicializa el dataset junto con el manejador de la conexión
	 * @param DBManager $manager
	 * @return void
	 */
	public function __construct($manager) {
		$this->manager = $manager;
	}

	/**
	 * Función mágica que asigna una propiedad al dataset
	 *
	 * Permite acceder directamente a una propiedad a través de su nombre
	 * @param string $name
	 * @param mixed $value
	 * @return void
	 */
	public function __set($name, $value) {
		$this->data[$name] = $value;
	}

	/**
	 * Función mágica que obtiene una propiedad del dataset
	 *
	 * Permite acceder directamente a una propiedad a través de su nombre
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name) {
		return isset($this->data[$name])?$this->data[$name]:NULL;
	}

	/**
	 * Devuelve un arreglo de pares columna, valor con los datos del dataset para usos internos del sistema
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
				$values[$key] = SV.$value.SV;
			}
			else
			{
				unset($values[$key]);
			}
		}
		return implode(', ', $values);
	}
}

// Fin del archivo

