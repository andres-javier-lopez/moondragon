<?php

class DBConnection {
    
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
	 * @var mysqli $connection
	 */
        
	private static $connection;
	
	/**
	 * Inicializa el objeto de conexión
	 * @return void
	 */
        
	public static function init()
	{
		self::$connection = mysqli_init();
	}
        
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
        
        /**
	 * Verifica si la conexion esta activa
	 * @return void
	 */
        
        public static function checkConnection()
	{
		if (!self::$connected)
		{
			self::defaultConnection();
		}
		elseif (!self::$connection->ping())
		{
			if(self::$connection->close())
			{
				self::$connected = false;
				self::defaultConnection();
			}
			else
			{
				throw new BadConnectionException('Ocurrió un error al verificar el estado activo de la conexión');
			}
		}
	}
        
        
        /**
	 * Conecta con la base de datos
	 * @return void
	 * @throws BadConnectionException
	 */
        
        private function defaultConnection()
	{
		if(is_null(self::$connection)) {
			throw new BadConnectionException('No se ha iniciado la conexión con la base de datos');
		}
		
		if(is_null(self::$selected))
		{
			self::$selected = Registry::get('DEFAULT_DB_PROFILE');
		}
		
		self::$connection->real_connect( self::$profiles[self::$selected]['host'], self::$profiles[self::$selected]['user'], self::$profiles[self::$selected]['password'], self::$profiles[self::$selected]['database'] );
		if (self::$connection->connect_errno)
		{
			throw new BadConnectionException( 'No se pudo establecer una conexión con el perfil '.self::$selected.': '.self::$connection->connect_error.'('.self::$connection->connect_errno.')');
		}
		
		self::$connected = true;
	}
        
        
        /**
	 * Lee los valores de la conexión desde un archivo XML
	 * @param string $file
	 * @return void
	 */
        
        public static function connectFromXML( $file )
	{
		if( !file_exists( $file ) )
		{
			throw BadConnectionException( 'No existe archivo de conexión' ); // @todo localizar contenido
		}
		
		self::init();
		
		$xml = simplexml_load_file( $file );
		
		foreach( $xml->profile as $profile )
		{
			self::createProfile( (string) $profile['id'], (string) $profile->host, (string) $profile->user, (string) $profile->password, (string) $profile->database );
		}
		
		self::checkConnection();
	}
        
        
        /**
	 * Devuelve el recurso de conexión actual
	 * @return mysqli
	 */
        
	public static function getConnection()
	{
		return self::$connection;
	}
        
        
}

