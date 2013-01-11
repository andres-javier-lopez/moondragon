<?php

/**
 * @brief Clase para el manejo de sesiones
 * 
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @author Noé Francisco Martínez  <noe.martinez@itca.edu.sv>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup Session
 */

class Session
{	
	/**
	 * Identificador de la aplicación actual
	 * @var string $app_id
	 */
	private static $app_id;
	
	/**
	 * Inicializa la sesión segun el identificador
	 * @param string $app_id 
	 * @return void
	 */
	public static function init($app_id = '')
	{
		session_start();
        
        if($app_id == '')
        {
        	trigger_error(_('La aplicación no tiene un id de sesión asignado'), E_USER_NOTICE);
        }
        
		self::$app_id = $app_id;	

		if(!isset($_SESSION['X_sess_'.self::$app_id.'_list']))
		{
			$_SESSION['X_sess_'.self::$app_id.'_list'] = array();
		}
		
		assert('self::$app_id == $app_id');
		assert('isset($_SESSION["X_sess_".self::$app_id."_list"])');
	}
	
   	/**
   	 * Método para acceder a las variables de sesión
   	 * @param string $var
   	 * @return mixed
   	 */
	public static function get( $var )
   	{
   		assert('isset(self::$app_id)');
   		if( isset($_SESSION[$var.'_'.self::$app_id] ))
   		{
   			assert('in_array($var."_".self::$app_id, $_SESSION["X_sess_".self::$app_id."_list"])');
   			return $_SESSION[$var.'_'.self::$app_id];
   		}
   		else
   		{
   			return NULL;
   		}
   		assert('false // No debemos de llegar hasta aquí');
   	}
   	
   	/**
   	 * Método para modificar las variables de sesión
   	 * @param string $var
   	 * @param mixed $value
   	 * @return void
   	 */
   	public static function set( $var , $value )
   	{
		$_SESSION[$var.'_'.self::$app_id] = $value;
		if(!in_array($var.'_'.self::$app_id, $_SESSION['X_sess_'.self::$app_id.'_list']))
		{
			$_SESSION['X_sess_'.self::$app_id.'_list'][] = $var.'_'.self::$app_id;
		}
		assert('in_array($var."_".self::$app_id, $_SESSION["X_sess_".self::$app_id."_list"])');
		assert('isset($_SESSION[$var."_".self::$app_id])');
   	}
   	
   	/**
   	 * Limpia todas las variables de sesión para la aplicación
   	 * @return void
   	 */
   	public static function clear()
   	{
   		$session_list = $_SESSION['X_sess_'.self::$app_id.'_list'];
   		if(!empty($session_list))
   		{
   			foreach($session_list as $session)
   			{
   				unset($_SESSION[$session]);
   				assert('!isset($_SESSION[$session])');
   			}
   		}
   		
   		unset($_SESSION['X_sess_'.self::$app_id.'_list']);
   		assert('!isset($_SESSION["X_sess_".self::$app_id."_list"])');
   	}
}

// Fin de archivo
