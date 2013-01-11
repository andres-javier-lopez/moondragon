<?php

/**
 * @brief Excepción general de bases de datos
 * 
 * Usualmente es preferible utilizar capturar una de las excepciones más específicas
 * derivadas de esta excepción
 */
class DatabaseException extends MoonDragonException {}

/**
 * @brief Error de conexión
 * 
 * Puede indicar que los datos de conexión son erróneos o el servidor de base de datos esta caído
 */
class BadConnectionException extends DatabaseException {}

/**
 * @brief Error al ejecutar una consulta
 * 
 * Indica un error devuelto por el gestor de base de datos durante la consulta. 
 * Puede significar que hay un error de sintaxis en la consulta o que se intento realizar
 * una operación no permitida
 */
class QueryException extends DatabaseException {}

/**
 * @brief Error al procesar sentencias preparadas
 *
 * Indica un error en la configuración de un objeto DBStatement. 
 * Usualmente implica un error en la lógica de la configuración.
 */
class StatementException extends DatabaseException {}


/**
 * @brief La consulta no devolvió resultados
 * 
 * Este es un error que se debe de capturar frecuentemete. 
 * Se debe manejar con un comportamiento alternativo ante una consulta vacía.
 */
class EmptyResultException extends DatabaseException {}

/**
 * @brief Error en la configuración del modelo
 * 
 * Implica un error en la lógica de configuración del modelo
 */
class ModelException extends DatabaseException {}

/**
 * @brief Error en una consulta de lectura
 * 
 * Indica que ocurrió un error en una consulta del tipo SELECT
 */
class ReadException extends QueryException {}

/**
 * @brief Error en una consulta de inserción
 * 
 * Indica que ocurrió un error en una consulta del tipo INSERT
 */
class CreateException extends QueryException {}

/**
 * @brief Error en una consulta de eliminación
 * 
 * Indica que ocurrió un error en una consulta del tipo DELETE
 */
class DeleteException extends QueryException {}

/**
 * @brief Error en una consulta de actualización
 * 
 * Indica que ocurrió un error en una consulta del tipo UPDATE
 */
class UpdateException extends QueryException {}

// Fin del archivo
