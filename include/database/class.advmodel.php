<?php

/// Excepción estándar del modelo AdvModel
/// @ingroup AdvDataExceptions
class AdvModelException extends QueryException {}

/**
 * Modelo avanzado para procesamiento de datos
 * 
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright TuApp.net - GNU Lesser General Public License
 * @date May 2012
 * @version 1.0
 * @ingroup AdvData
 */
abstract class AdvModel extends Model
{
	/**
	 * Número total de páginas para paginación
	 * @var int $total_pages
	 */
	protected $total_pages = 0;
	
	/**
	 * Devuelve el número total de páginas para paginación
	 * @return int
	 */
	public function getTotalPages()
	{
		return $this->total_pages;
	}
	
	/**
	 * Construye la consulta para LIMIT de MySQL
	 * @param int $page número de página
	 * @param int $xpage cantidad de registros por página
	 * @param int $max número total de registros
	 * @return string
	 */
	protected function getLimit($page, $xpage, $max)
	{
		if($xpage == 0)
		{
			return '';
		}
		
		$this->total_pages = ceil($max/$xpage);
		
		if($page < 1) { $page = 1; }
		if($page > $this->total_pages && $this->total_pages != 0){ $page = $this->total_pages; }
		
		$start = ($page - 1)*$xpage;
		$limit = 'LIMIT '.$start.','.$xpage;
		return $limit;
	}
	
	/**
	 * Devuelve una única variable del primer resultado de la consulta
	 * @param string $sql consulta SQL
	 * @param string $var nombre de la columna de la que se obtiene el valor
	 * @return string
	 * @throws AdvModelException
	 */
	protected function getVar($sql, $var)
	{
		try
		{
			$result = $this->db->query($sql);
			if($this->db->numRows($result) == 0)
			{
				throw new AdvModelException('No se recuperaron registros');
			}
			$data = $this->db->result($result, $var);
		}
		catch(AdvModelException $ex)
		{
			throw $ex;
		}
		catch(QueryException $ex)
		{
			throw new AdvModelException('No se pudo ejecutar la query '.$sql);
		}
		
		if($data === false)
		{
			throw new AdvModelException('No se pudo obtener la columna '.$var);
		}
		
		return $data;
	}
	
	/**
	 * Obtiene el primer registro devuelto por la consulta
	 * @param string $sql
	 * @return object
	 * @throws AdvModelException 
	 */	
	protected function getRow($sql)
	{
		try
		{
			$result = $this->db->query($sql);
			if($this->db->numRows($result) == 0)
			{
				throw new AdvModelException('No se recuperaron registros');
			}
			$data = $this->db->fetch($result);
		}
		catch(QueryException $ex)
		{
			throw new AdvModelException('No se pudo ejecutar la query '.$sql);
		}

		return $data;
	}
	
	/**
	 * Devuelve el arreglo de registros retornados por la consulta
	 * @param string $sql
	 * @return array
	 * @throws AdvModelException
	 */
	protected function getRows($sql)
	{
		try
		{
			$result = $this->db->query($sql);
			$data = array();
			while($row = $this->db->fetch($result))
			{
				$data[] = $row;
			}
		}
		catch(QueryException $ex)
		{
			throw new AdvModelException('No se pudo ejecutar la query '.$sql);
		}

		return $data;
	}
	
	/**
	 * Ejecuta una consulta y no espera un valor de retorno
	 * @param string $sql
	 * @return void
	 * @throws AdvModelException
	 */
	protected function exec($sql)
	{
		try 
		{
			$this->db->query($sql);
		}
		catch(QueryException $ex)
		{
			throw new AdvModelException('No se pudo ejecutar la query '.$sql);
		}
	}
}

//Fin de archivo
