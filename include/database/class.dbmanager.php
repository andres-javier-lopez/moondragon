<?php

class DBManager {
    
                public function __construct()
                {
		$this->checkConnection();
                }
        
    
                public function query( $query )
                {
                    
		$this->checkConnection();
		
		if( $this->connection->real_query($query) === false )
		{
			$this->query_history .= 'Error: '.$query."\n";
			throw new QueryException( 'Ha ocurrido un error en la query: '.$this->errorMessage()."\n" );
		}
		
		$this->query_history .= $query."\n";
		
		if($this->connection->field_count > 0)
		{
			$result = $this->connection->store_result();
			
			if($result === false)
			{
				throw new QueryException('No se puedo recuperar el resultado: '.$this->errorMessage()."\n");
			}
			
			return $result;
		}
                
		else
		{
			return true;
		}
                
                }
        
        
        /**
	 * Ejecuta multiples sentencias SQL
	 * @param string $query
	 * @return void
	 * @throws QueryException
	 */
                
	public function multiquery( $query )
	{
		$this->checkConnection();
		
		// Las queries múltiples no se guardan en el log porque son demasiado grandes
		if( @$this->connection->multi_query($query) === false )
		{
			throw new QueryException( 'Ha ocurrido un error en la query múltiple: '.$this->errorMessage()."\n" );
		}
		
		$result = array();
		do {
			$result[] = $this->connection->store_result();
		}while($this->connection->next_result());
		
		if($this->connection->errno)
		{
			throw new QueryException( 'Ha ocurrido un error en la query múltiple: '.$this->errorMessage()."\n" );
		}
		
		return $result;
	}
	
	/**
	 * Inicia la ejecución de una transacción
	 * @return void
	 * @throws QueryException
	 */
        
	public function startTran()
	{
		$this->connection->autocommit(false);
	}
	
	/**
	 * Finaliza la ejecución de una transacción
	 * @return void
	 * @throws QueryException
	 */
        
	public function commit()
	{
		$this->connection->commit();
		$this->connection->autocommit(true);
	}
	
	/**
	 * Revierte una transacción no finalizada
	 * @return void
	 * @throws QueryException
	 */
        
	public function rollback()
	{
		$this->connection->rollback();
		$this->connection->autocommit(true);
	}
	
	/**
	 * Devuelve los datos de una fila dentro del resultado
	 * @param mysqli_result $result
	 * @param int $type F_OBJECT, F_ASSOC, F_ROW
	 * @return object|array
	 * @throws DBException
	 */
        
	public function fetch( $result, $type = self::F_OBJECT )
	{
		switch( $type )
		{
			case self::F_OBJECT:
				return $result->fetch_object();
			case self::F_ROW:
				return $result->fetch_row();	
			case self::F_ASSOC:
				return $result->fetch_assoc();
			default:
				throw DBException('Se ha enviado un parametro inválido a la función fetch');
		}
	}

	/**
	 * Obtiene un campo específico de una fila
	 * @param mysqli_result $result
	 * @param string $field
	 * @param int $row
	 * @return string
	 * @throws DBException
	 */
	public function result( $result, $field, $row = 0 )
	{
		if($row < $result->num_rows)
		{
			$result->data_seek($row);
			$data = $result->fetch_assoc();
			$result->data_seek(0);
			
			if(isset($data[$field]))
			{
				return $data[$field];
			}
			else
			{
				throw new DBException('El campo buscado no se encuentra en los valores devueltos');
			}
		}
		else
		{
			throw new DBException('El resultado buscado excede la cantidad de filas devueltas');
		}
		
	}

	/**
	 * Devuelve el id autoincrementado de la ultima fila insertada
	 * @return int
	 */
	public function insertId()
	{
		return $this->connection->insert_id;
	}
	
	/**
	 * Devuelve el número de filas para el recurso
	 * @param mysqli_result $result
	 * @return int
	 */
	public function numRows( $result )
	{
		return $result->num_rows;
	}
	
	/**
	 * Evalúa los valores a ingresar en las sentencias SQL
	 * @param string $value
	 * @return string
	 */
	public function evalSQL( $value ) 
	{		
		if ( get_magic_quotes_gpc() )
		{
			stripslashes( $value );
		}
		
		$this->checkConnection();
		$value = $this->connection->real_escape_string( $value );
		
		return $value;
	}
	
	/**
	 * Devuelve el historial de consultas realizadas
	 * @return string
	 */
	public function showQueries()
	{
		return $this->query_history;
	}

	/**
	 * Muestra el mensaje de error de mysql
	 * @return string
	 */
	private function errorMessage()
	{
		return $this->connection->error.' ('.$this->connection->errno.')';
	}
	
	/**
	 * Comprueba la conexión activa
	 * @return void
	 */
	private function checkConnection()
	{
		DBConnection::checkConnection();
		$this->connection = DBConnection::getConnection();
	}
}

// Fin de archivo

    
	
