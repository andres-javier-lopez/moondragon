<?php

/**
 * @brief Manager que automáticamente reformatea todas las respuestas en cadenas JSON
 * 
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup Manager
 */
 
abstract class JSONManager extends Manager
{
	/**
	 * Convierte la respuesta en formato JSON
	 * @param mixed $data
	 * @return string
	 */
	protected function formatResponse($data) {
		header('Content-Type: application/json');
		return json_encode($data);
	}
	
	/**
	 * Devuelve una respuesta de éxito
	 * @return array
	 */
	protected function success() {
		return array('success' => true);
	}
	
	/**
	 * Devuelve una respuesta de fracaso
	 * @return array
	 */
	protected function failure() {
		return array('success' => false);
	}
}

// Fin del archivo

