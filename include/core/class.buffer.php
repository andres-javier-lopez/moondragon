<?php

/**
 * @brief Maneja un arreglo de buffers de salida
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup MoonDragon
 */

class Buffer
{	
	/**
	 * Arreglo que almacena los buffers de salida
	 * @var array $buffer
	 */
	private static $buffer = array();

	/**
	 * Pila que almacena los identificadores de los buffers
	 * @var array $stack
	 */
	private static $stack = array();

	/**
	 * Activa un buffer de salida con el indentificador asignado
	 * @param string $buffer_id
	 * @return void
	 */
	public static function start( $buffer_id )
	{
		self::$stack[] = $buffer_id;

		ob_start();
	}

	/**
	 * Finaliza el buffering de la salida y almacena su contenido
	 * @return void
	 */
	public static function end()
	{
		$buffer_id = array_pop( self::$stack );

		$contents = ob_get_contents();

		if( $contents && $buffer_id )
		{
			if( isset( self::$buffer[ $buffer_id ] ))
			{
				self::$buffer[ $buffer_id ] .= $contents;
			}
			else
			{
				self::$buffer[ $buffer_id ] = $contents;
			}
			
			ob_end_clean();
		}
	}
	
	/**
	 * Devuelve el contenido del buffer de salida que se encuentra almacenado con el id especificado
	 * @param string $buffer_id
	 * @param boolean $clean Limpia el contenido del buffer, por defecto es verdadera
	 * @return string Si el buffer no existe devuelve una cadena vacía
	 */
	public static function getContent( $buffer_id, $clean = true )
	{
		if( !isset( self::$buffer[$buffer_id] ) )
		{
			return '';
		}
		
		$buffer = self::$buffer[$buffer_id];
		
		if( $clean )
		{
			self::$buffer[$buffer_id] = '';
		}
		
		return $buffer;		
	}
	
	/**
	 * Limpia el contenido almacenado del buffer con el id especificado
	 * @param string $buffer_id
	 * @return void
	 */
	public static function clean( $buffer_id )
	{
		self::$buffer[$buffer_id] = '';
	}
	
	/**
	 * Escribe el contenido directamente en el buffer almacenado
	 * @param string $buffer_id
	 * @param string $content
	 * @param boolean $overwrite Si es verdadero sobreescribe el contenido anterior. Por defecto es falso.
	 * @return void
	 */
	public static function write( $buffer_id, $content, $overwrite = false )
	{
		if( !isset(self::$buffer[$buffer_id]) || $overwrite )
		{
			self::$buffer[$buffer_id] = $content;
		}
		else
		{
			self::$buffer[$buffer_id] .= $content;
		}
	}
}


// Fin de archivo
