<?php

/**
 * @brief Clase para almacenar y cargar los modelos de la base de datos
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup Database
 */

class ModelLoader 
{
	/**
	 * Variable estática que almacena la configuración de los modelos
	 * @var array $config
	 */
	public static $config;
	
	/**
	 * Almacena la configuración de un modelo
	 * @param string $modelname
	 * @param array $modelconfig
	 * @return void
	 */
	public static function addModel($modelname, $modelconfig) {
		self::$config[$modelname] = $modelconfig;
	}
	
	/**
	 * Instancia un modelo específico
	 * @param string $modelname
	 * @return Model
	 * @throws ModelException
	 */
	public static function getModel($modelname) {
		if(!isset(self::$config[$modelname])){
			throw new ModelException(sprintf(_("No existe el modelo %s"), $modelname));
		}
		
		$model = new Model(Database::getManager(), self::$config[$modelname]);
		return $model;		
	}
}

// Fin del archivo

