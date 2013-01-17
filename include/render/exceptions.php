<?php

/**
 * @brief Excepción general del módulo
 * 
 * Indica principalmente errores de configuración
 */
class RenderException extends MoonDragonException {}

/**
 * @brief Plantilla no encontrada
 * 
 * La plantilla solicitada al módulo no se encuentra en el directorio de plantillas proporcionado.
 */
class TemplateNotFoundException extends RenderException {}

// Fin del archivo
