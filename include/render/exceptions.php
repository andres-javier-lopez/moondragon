<?php

/// Excepciones del módulo render
/// @ingroup Render
class RenderException extends MoonDragonException {}

/// Excepción de plantilla no encontrada
/// @ingroup Render
class TemplateNotFoundException extends RenderException {}
