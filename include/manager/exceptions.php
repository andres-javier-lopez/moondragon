<?php

/// Excepción general del módulo Manager
/// @ingroup Manager
class ManagerException extends MoonDragonException {}

/// Error 404 causado por problemas con la tarea
/// @ingroup Manager
class TaskException extends Status404Exception {}

/// Error 404 causado por el ruteo de la url
/// @ingroup Manager
class RouteException extends Status404Exception {}
