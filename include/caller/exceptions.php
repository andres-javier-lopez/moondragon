<?php

/**
 * @brief Excepción general del módulo
 * 
 * Esta es la excepción general de la que derivan las demás, normalmente se capturaran
 * las excepciones más específicas que derivan de esta
 */
class CallerException extends MoonDragonException {}

/**
 * @brief Error con el url del API
 * 
 * Este error indica que se proporciono mal el url del API
 */
class BadApiUrlException extends CallerException {}

/**
 * @brief Los datos de envío están vacíos
 * 
 * Verificar que existan datos de envío para los procesos POST y PUT
 */
class EmptyDataException extends CallerException {}

/**
 * @brief Error en los procesos de la librería CURL
 * 
 * Error específico de CURL, podría indicar que el servidor de API esta caído
 */
class CurlException extends CallerException {}

/**
 * @brief Error con procesamiento de JSON
 * 
 * El JSON recibido desde el API esta mal formado o presenta otro tipo de problemas
 */
class JsonException extends CallerException {}

// Fin del archivo
