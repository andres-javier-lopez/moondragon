<?php

/**
 * @defgroup Mailer Mailer
 * @brief Módulo para manejo de correos electrónicos
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @ingroup MoonDragon
 * 
 * @exception MailerException Excepción general del módulo
 * @exception MailConfException Excepción durante la configuración del envío de correos
 * @exception MailSendException Excepción durante el envío del correo electrónico
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
