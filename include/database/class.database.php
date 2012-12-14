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

	public static function createProfile( $profile, $engine, $host, $user, $password, $database )
	{
		self::$profiles[$profile]['engine'] = $engine;
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

	public static function connectProfile( $name )
	{
		if( isset( self::$profiles[$name] ) )
		{
			self::connect(self::$profiles[$name]['engine'], self::$profiles[$name]['host'], self::$profiles[$name]['user'], self::$profiles[$name]['password'], self::$profiles[$name]['database']);
		}
		else
		{
			throw BadConnectionException(_('No existe el perfil seleccionado'));
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

