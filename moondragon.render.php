<?php

/**
 * @defgroup Render Render
 * @brief Módulo para manejo de plantillas
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup MoonDragon
 * 
 * @exception RenderException Excepción general del módulo
 * @exception TemplateNotFoundException Plantilla no encontrada
 */

if(!defined('MOONDRAGON_PATH')) {
	define('MOONDRAGON_PATH', dirname(__FILE__));
	set_include_path(get_include_path() . PATH_SEPARATOR . MOONDRAGON_PATH);
}

assert("defined('MOONDRAGON_PATH')");

require_once 'moondragon.core.php';

require_once 'include/render/exceptions.php';
require_once 'include/render/class.vars.php';
require_once 'include/render/class.template.php';
