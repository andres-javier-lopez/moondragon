<?php

class MySQLConnection implements DBConnection{
	
	protected $connection;
	
	protected $host;
	
	protected $user;
	
	protected $password;
	
	protected $database;
	
	/**
	 * Inicializa el objeto de conexión
	 * @return void
	 */
	
	public function __construct($host, $user, $password, $database)
	{
		$this->connection = mysqli_init();
		$this->host = $host;
		$this->user = $user;
		$this->password = $password;
		$this->database = $database;
		$this->defaultConnection();
	}
	
	/**
	 * Conecta con la base de datos
	 * @return void
	 * @throws BadConnectionException
	 */
	
	private function defaultConnection()
	{
		if(is_null($this->connection)) {
			throw new BadConnectionException(_('No se ha iniciado la conexión con la base de datos'));
		}
	
		$this->connection->real_connect( $this->host, $this->user, $this->password, $this->database );
		if ($this->connection->connect_errno)
		{
			throw new BadConnectionException(_('No se pudo establecer una conexión con la base de datos ').': '.self::$connection->connect_error.'('.self::$connection->connect_errno.')');
		}
	
		$this->connected = true;
	}
	
	/**
	 * Verifica si la conexion esta activa
	 * @return void
	 */
	
	public function checkConnection()
	{
		if (!$this->connected)
		{
			$this->defaultConnection();
		}
		elseif (!$this->connection->ping())
		{
			if($this->connection->close())
			{
				$this->connected = false;
				$this->defaultConnection();
			}
			else
			{
				throw new BadConnectionException(_('Ocurrió un error al verificar el estado activo de la conexión'));
			}
		}
	}
	
	/**
	 * Devuelve el recurso de conexión actual
	 * @return mysqli
	 */
	
	public function getConnection()
	{
		return $this->connection;
	}
	
	public function getManager()
	{
		return new MySQLManager($this);
	}
	
}
