<?php

/**
 * @brief Interfaz para partes del sistema que pueden ejecutarse
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup MoonDragon
 */

interface Runnable {
	
	/**
	 * Ejecución del objeto
	 * @return void
	 * @throws MoonDragonException
	 */
	public function run();
}

// Fin del archivo
