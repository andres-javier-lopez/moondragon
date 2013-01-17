<?php

/**
 * @brief Clase para procesar datos en JSON
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup Caller
 */

class Json {

	/**
	 * Decodifica una cadena de JSON y captura los errores durante la decodificación
	 * @param string $json_data
	 * @return array
	 * @throws JsonException
	 */
	public static function decode($json_data) {
		$data = json_decode($json_data);
		if($data === false || is_null($data)) {
			switch(json_last_error()) {
				case JSON_ERROR_NONE: $error = _('sin errores');
				break;
				case JSON_ERROR_DEPTH: $error = _('alcazada profundidad máxima');
				break;
				case JSON_ERROR_STATE_MISMATCH: $error = _('json inválido o mal formado');
				break;
				case JSON_ERROR_CTRL_CHAR: $error = _('caracter de control inesperado');
				break;
				case JSON_ERROR_SYNTAX: $error = _('error de sintaxis');
				break;
				case JSON_ERROR_UTF8: $error = _('codificacion incorrecta de caracteres');
				break;
				default: $error = _('error desconocido');
			}
			throw new JsonException(_('No se pudo decodificar JSON').'('.$error.')');
		}
		assert('$data !== false');
		assert('!is_null($data)');
		return $data;
	}
}

// Fin del archivo
