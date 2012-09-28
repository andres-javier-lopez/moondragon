<?php

/**
 * Clase para realizar lecturas a la base de datos
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @date Sep 2012
 * @version 1
 * @ingroup Database
 */

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

	public function setWhere($where) {
		// Esta esta en desarrollo
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
