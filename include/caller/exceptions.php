<?php

/// Excepción general del módulo Caller
/// @ingroup Caller
class CallerException extends MoonDragonException {}

/// Error con URL de API
/// @ingroup Caller
class BadApiUrlException extends CallerException {}

/// Error al recibir datos vacíos
/// @ingroup Caller
class EmptyDataException extends CallerException {}

/// Error en librería CURL
/// @ingroup Caller
class CurlException extends CallerException {}

/// Error con procesamiento de JSON
/// @ingroup Caller
class JsonException extends CallerException {}
