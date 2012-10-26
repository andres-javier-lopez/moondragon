<?php

/// Excepción general del módulo Database
/// @ingroup Database
class DatabaseException extends MoonDragonException {}

/// Error de conexión
/// @ingroup Database
class BadConnectionException extends DatabaseException {}

/// Error al ejecutar una consulta
/// @ingroup Database
class QueryException extends DatabaseException {}

/// Error al procesar sentencias preparadas
/// @ingroup Database
class StatementException extends DatabaseException {}

/// La consulta no devolvió resultados
/// @ingroup Database
class EmptyResultException extends DatabaseException {}

/// Error en modelo
/// @ingroup Database
class ModelException extends DatabaseException {}

/// Error en consulta de lectura
/// @ingroup Database
class ReadException extends QueryException {}

/// Error en consulta de creación
/// @ingroup Database
class CreateException extends QueryException {}

/// Error en consulta de eliminación
/// @ingroup Database
class DeleteException extends QueryException {}

/// Error en consulta de actualización
/// @ingroup Database
class UpdateException extends QueryException {}
