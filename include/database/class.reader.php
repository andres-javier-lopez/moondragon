<?php

/**
 * Clase para realizar lecturas a la base de datos
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @date Sep 2012
 * @version 2
 * @ingroup Database
 */

class Reader extends TableData
{
	protected $order;
	
	protected $where;
	
	protected $vars;

	public function __construct($manager)
	{
		$this->manager = $manager;
		$this->where = '';
		$this->vars = array();
	}

	public function setOrder($order) {
		$this->order = $order;
		// Permite encadenamiento de objetos
		return $this;
	}

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
			$string = '`'.$where.'` = "%s"';
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
		$sql = 'SELECT '.$this->getFieldsAndId().' FROM `'.$this->table.'`';
		
		// Implementando sistema de where
		if($this->where != '') {
			$sql .= $this->where;
		}

		// Verificamos si hay una cláusula de order
		if(isset($this->order))
		{
			$sql .= ' ORDER BY '.$this->order.' ';
		}

		// Implementando nuevo sistema
		try {
			// Evaluamos si se agregaron variables a la consulta			
			if(!empty($this->vars)) {
				$result = $this->manager->getQuery($sql)->addParams($this->vars)->getResult();
			}
			else {
				$result = $this->manager->query($sql);
			}
		}
		catch(QueryException $e) {
			throw new ReadException($e->getMessage());
		}
		return $result;
	}
}
