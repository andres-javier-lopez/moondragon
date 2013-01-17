<?php

/**
 * @brief Clase estática para obtener variables REQUEST
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup MoonDragon
 */


class Request
{
	/**
	 * Variable que controla el modo de pruebas
	 * @var boolean $test_mode
	 */
	public static $test_mode = false;
	
	/**
	 * Valores de prueba para el sistema
	 * @var array $test_values
	 * 
	 */
	public static $test_values = array();
	
	/**
	 * Devuelve la variable POST indicada
	 * @param string $id
	 * @param boolean $filter determina si el resultado se filtra contra XSS, por defecto falso
	 * @return string
	 * @throws RequestException
	 */
	public static function getPOST($id, $filter = false)
	{
		if(self::$test_mode) {
			return self::getTestValue($id);
		}
		
		if(!isset($_POST[$id]))
		{
			throw new RequestException(sprintf(_('No se ha recibido la variable %s en POST'), $id));
		}
	
		$value = $_POST[$id];
		if($filter == true)
		{
			$value = self::filterXSS($value);
		}
	
		return $value;
	}
	
	/**
	 * Devuelve la variable POST indicada o la variable alternativa si no se existe
	 * @param string $id
	 * @param string $alt variable alternativa
	 * @param boolean $filter determina si el resultado se filtra contra XSS, por defecto falso
	 * @return string
	 * @throws RequestException
	 */
	public static function tryGetPOST($id, $alt, $filter = false)
	{
		if(self::$test_mode) {
			return self::getTestValue($id, $alt);
		}
		
		if(!isset($_POST[$id]))
		{
			return $alt;
		}
	
		$value = $_POST[$id];
		if($filter == true)
		{
			$value = self::filterXSS($value);
		}
	
		return $value;
	}
	
	/**
	 * Devuelve la variable GET indicada
	 * @param string $id
	 * @param boolean $filter determina si el resultado se filtra contra XSS, por defecto falso
	 * @return string
	 * @throws RequestException
	 */
	public static function getGET($id, $filter = false)
	{
		if(self::$test_mode) {
			return self::getTestValue($id);
		}
		
		if(isset($_GET[$id]))
		{
			$value = $_GET[$id];
		}
		else
		{
			// Las variables de pathinfo no están soportadas actualmente
				
			/*if(PathVars::hasVars() == true)
				{
			try
			{
			$value = PathVars::get($id);
			}
			catch(PathVarsException $e)
			{
			throw new RequestException(sprintf(_('No se ha recibido la variable %s en GET'), $id));
			}
			}
			else
			{*/
			throw new RequestException(sprintf(_('No se ha recibido la variable %s en GET'), $id));
			//}
		}
	
		if($filter == true)
		{
			$value = self::filterXSS($value);
		}
	
		return $value;
	}
	
	/**
	 * Devuelve la variable GET indicada o la variable alternativa si no existe
	 * @param string $id
	 * @param string $alt variable alternativa
	 * @param boolean $filter determina si el resultado se filtra contra XSS, por defecto falso
	 * @return string
	 * @throws RequestException
	 */
	public static function tryGetGET($id, $alt, $filter = false)
	{
		if(self::$test_mode) {
			return self::getTestValue($id, $alt);
		}
		
		if(isset($_GET[$id]))
		{
			$value = $_GET[$id];
		}
		else
		{
			// Variables de pathinfo no soportadas
				
			/*if(PathVars::hasVars() == true)
				{
			try
			{
			$value = PathVars::get($id);
			}
			catch(PathVarsException $e)
			{
			return $alt;
			}
			}
			else
			{*/
			return $alt;
			//}
		}
	
		if($filter == true)
		{
			$value = self::filterXSS($value);
		}
	
		return $value;
	}
	
	/**
	 * Devuelve la variable que sea de POST o GET
	 * @param string $id
	 * @param boolean $filter determina si el resultado se filtra contra XSS, por defecto falso
	 * @return string
	 * @throws RequestException
	 */
	public static function getREQUEST($id, $filter = false)
	{
		if(self::$test_mode) {
			return self::getTestValue($id);
		}
		
		if(isset($_REQUEST[$id]))
		{
			$value = $_REQUEST[$id];
		}
		else
		{
			// Variables de pathinfo no soportadas
				
			/*if(PathVars::hasVars() == true)
				{
			try
			{
			$value = PathVars::get($id);
			}
			catch(PathVarsException $e)
			{
			throw new RequestException('No se ha recibido la variable '.$id.' en POST ni GET');
			}
			}
			else
			{*/
			throw new RequestException(sprintf(_('No se ha recibido la variable %s en POST ni GET'), $id));
			// }
		}
	
		if($filter == true)
		{
			$value = self::filterXSS($value);
		}
	
		return $value;
	}
	
	/**
	 * Devuelve la variable que sea de POST o GET, o la variable alternativa si no existe
	 * @param string $id
	 * @param string $alt variable alternativa
	 * @param boolean $filter determina si el resultado se filtra contra XSS, por defecto falso
	 * @return string
	 * @throws RequestException
	 */
	public static function tryGetREQUEST($id, $alt, $filter = false)
	{
		if(self::$test_mode) {
			return self::getTestValue($id, $alt);
		}
		
		if(isset($_REQUEST[$id]))
		{
			$value = $_REQUEST[$id];
		}
		else
		{
			// Variables de pathinfo no soportadas
				
			/*if(PathVars::hasVars() == true)
				{
			try
			{
			$value = PathVars::get($id);
			}
			catch(PathVarsException $e)
			{
			return $alt;
			}$this->
			}
			else
			{*/
			return $alt;
			// }
		}
	
		if($filter == true)
		{
			$value = self::filterXSS($value);
		}
	
		return $value;
	}
	
	/**
	 * Funcion que devuelve valores de prueba
	 * @param string $id
	 * @param string $alt
	 * @return string 
	 */
	
	private static function getTestValue($id, $alt = NULL) {
		if(isset($test_values[$id])) {
			return self::filterXSS($test_values[$id]);
		}
		elseif(!is_null($alt)) {
			return self::filterXSS($alt);
		}
		else {
			return self::filterXSS($id);
		}
	}
	
	/**
	 * Filtra la variable recibida contra ataques XSS
	 * @param string $data
	 * @return string
	 */
	private static function filterXSS($data)
	{
		if(is_array($data)) {
			foreach($data as $key => $dat) {
				$data[$key] = self::filterXSS($dat);
			}
		}
		else {
			$data = str_replace('&', '&amp;', $data);
			$data = str_replace('#', '&#35;', $data);
			$data = str_replace('<', '&lt;', $data);
			$data = str_replace('>', '&gt;', $data);
			$data = str_replace('(', '&#40;', $data);
			$data = str_replace(')', '&#41;', $data);
			$data = str_replace('"', '&quot;', $data);
			$data = str_replace("'", '&#39;', $data);
		}
	
		return $data;
	}
}

// Fin de archivo

