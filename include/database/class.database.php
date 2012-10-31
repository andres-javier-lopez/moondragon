<?php

/**
 * Clase estática para el manejo de bases de datos
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio - www.klanestudio.com
 * @ingroup Database
 */

class Database {

	/**
	 * Perfiles de conexión
	 * @var array $profiles
	 */

	private static $profiles = array();

	/**
	 * Perfil de conexión activo
	 * @var string $selected
	 */

	private static $selected;

	/**
	 * Bandera para controlar el estado de la conexion
	 * @var boolean $connected
	 */

	private static $connected = false;

	/**
	 * Mantiene el recurso de conexion
	 * @var DBConnection $connection
	 */

	private static $connection;



	/**
	 * Crea un nuevo perfil de conexión
	 * @param string $name
	 * @param string $host
	 * @param string $user
	 * @param string $password
	 * @param string $database
	 * @return void
	 */

	public static function createProfile( $profile, $host, $user, $password, $database )
	{
		self::$profiles[$profile]['host'] = $host;
		self::$profiles[$profile]['user'] = $user;
		self::$profiles[$profile]['password'] = $password;
		self::$profiles[$profile]['database'] = $database;
	}

	/**
	 * Selecciona el perfil de conexión
	 * @param string $name
	 * @return void
	 * @throws BadConnectionException
	 */

	public static function selectProfile( $name )
	{
		if( isset( self::$profiles[$name] ) )
		{
			$previous = self::$selected;
			self::$selected = $name;
			self::$connected = false;
			try {
				self::checkConnection();
			}catch(BadConnectionException $e)
			{
				self::$selected = $previous;
				throw $e;
			}
		}
		else
		{
			throw BadConnectionException('No existe el perfil seleccionado');
		}
	}

	//
	public static function connect($engine, $host, $user, $password, $database) {
		switch(strtolower($engine)) {
			case 'mysql':
				require_once 'include/database/mysql/init.php';
				self::$connection = new MySQLConnection($host, $user, $password, $database);
				break;
			default:
				throw new BadConnectionException(_('Gestor de base de datos no soportado'));
		}
	}
	
	public static function getManager() {
		return self::$connection->getManager();
	}


}

