<?php

/**
 * @defgroup MoonDragon MoonDragon PHP
 *
 * @author Andrés Javier López <ajavier.lopez@gmail.com>
 * @copyright Klan Estudio (www.klanestudio.com) - GNU Lesser General Public License
 * @date Sep 2012
 * @version 4.0 b1
 */

if(!defined('MOONDRAGON_PATH')) {
    define('MOONDRAGON_PATH', dirname(__FILE__));
    set_include_path(get_include_path() . PATH_SEPARATOR . MOONDRAGON_PATH);
}

assert("defined('MOONDRAGON_PATH')");

require_once 'include/core/locale.php';
require_once 'include/core/exceptions.php';
require_once 'include/core/interfaces.php';
require_once 'include/core/class.moondragon.php';
require_once 'include/core/class.buffer.php';

// Error Control

assert_options(ASSERT_BAIL, true);