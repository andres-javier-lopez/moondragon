<?php

/**
 * @brief Clase para manejar la conexión con una base de datos MySQL
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup MySQL
 */

class MySQLConnection implements DBConnection{
	
	/**
	 * Recurso de la conexión
	 * @var mysqli $connection
	 */
	protected $connection;
	
	/**
	 * Servidor de base de datos
	 * @var string $host
	 */
	protected $host;
	
	/**
	 * Usuario de la base de datos
	 * @var string $user
	 */
	protected $user;
	
	/**
	 * Contraseña de la base de datos
	 * @var string $password
	 */
	protected $password;
	
	/**
	 * Nombre de la base de datos
	 * @var string $database
	 */
	protected $database;
	
	/**
	 * Inicializa el objeto de conexión
	 * @param string $host
	 * @param string $user
	 * @param string $password
	 * @param string $database
	 * @return void
	 * @throws BadConnectionException
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
	 * @return MySQLConnection Este método se puede encadenar
	 * @throws BadConnectionException
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
		
		return $this;
	}
	
	/**
	 * Devuelve el recurso de conexión actual
	 * @return mysqli
	 */	
	public function getConnection()
	{
		return $this->connection;
	}
	
	/**
	 * Devuelve el manejador de la base de datos
	 * @return MySQLManager
	 */
	public function getManager()
	{
		return new MySQLManager($this);
	}
	
}

// Fin del archivo
