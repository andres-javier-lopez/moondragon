<?php

/**
 * @brief Excepción general del módulo
 * 
 * Esta asociada principalemente a errores en los procesos internos del módulo.
 * También puede ser utilizada por el desarrollador para indicar errores durante la ejecución.
 */
class ManagerException extends MoonDragonException {}

/**
 * @brief Error 404 por tareas no encontradas
 * 
 * Es manejado automáticamente por el sistema y significa que la tarea no existe dentro del Manager
 */
class TaskException extends Status404Exception {}

/**
 * @brief Error 404 asociado con la ruta
 * 
 * Es manejado automáticamente por el sistema y significa que la ruta no apunta a ningún Manager
 */
class RouteException extends Status404Exception {}

// Fin del archivo
