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
		$json = json_encode($data);
		if($json === false) {
			return json_encode($this->error(_('No se pudo codificar la respuesta')));
		}
		else {
			return $json;
		}
	}
	
	/**
	 * Devuelve una respuesta de éxito
	 * @return array
	 */
	protected function isSuccess() {
		return array('success' => true);
	}
	
	/**
	 * Devuelve una respuesta de éxito junto con datos
	 * @return array
	 */
	protected function isData($data) {
		return array('success' => true, 'data' => $data);
	}
	
	/**
	 * Devuelve una respuesta de fracaso
	 * @return array
	 */
	protected function isFailure() {
		return array('success' => false);
	}
	
	/**
	 * Devuelve una respuesta de fracaso junto con un error
	 * @return array
	 */
	protected function isError($error) {
		return array('success' => false, 'error' => $error);
	}
}

// Fin del archivo

