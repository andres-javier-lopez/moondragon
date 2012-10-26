<?php

/**
 * Clase para el manejo de sesiones
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
	 * Inicializa la sesión
	 * @return void
	 */
	public static function init()
	{
		session_start();
        
        if(!file_exists('app_id'))
        {
            if(is_writable('.'))
            {
                $app_id = dechex(rand(268435456, 4294967295));
                file_put_contents('app_id', $app_id);
            }
            else
            {
                trigger_error('No se puede generar el id de sesión para la aplicación', E_USER_WARNING);
                self::$app_id = '';
                return; 
            }
        }
        
		self::$app_id = file_get_contents('app_id');	

		if(!isset($_SESSION['X_sess_'.self::$app_id.'_list']))
		{
			$_SESSION['X_sess_'.self::$app_id.'_list'] = array();
		}
	}
	
   	/**
   	 * Método para acceder a las variables de sesión
   	 * @param string $var
   	 * @return mixed
   	 */
	public static function get( $var )
   	{
   		if( isset($_SESSION[$var.'_'.self::$app_id] ))
   		{
   			return $_SESSION[$var.'_'.self::$app_id];
   		}
   		else
   		{
   			return NULL;
   		}   		
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
   			}
   		}
   		
   		unset($_SESSION['X_sess_'.self::$app_id.'_list']);
   	}
}

// Fin de archivo