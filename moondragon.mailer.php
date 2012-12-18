<?php

/**
 * @defgroup Mailer Módulo para manejo de correos electrónicos
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup MoonDragon
 */

if(!defined('MOONDRAGON_PATH')) {
    define('MOONDRAGON_PATH', dirname(__FILE__));
    set_include_path(get_include_path() . PATH_SEPARATOR . MOONDRAGON_PATH);
}

assert("defined('MOONDRAGON_PATH')");

require_once 'moondragon.core.php';

require_once 'include/mailer/exceptions.php';
require_once 'include/mailer/class.phpmailer.php';
require_once 'include/mailer/class.mailer.php';
