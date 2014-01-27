<?php

/**
 * Default settings références for IDE documentation
 *
 * PHP version 5
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */

define('SJO_DEBUG', false);
define('SJO_CHARSET', 'UTF-8');
define('SJO_DEFAULT_LOCALE', 'en_US');
define('SJO_TIMEZONE', 'Europe/Paris');

define('SJO_ROOT', '..');
define('SJO_ROOT_APP', SJO_ROOT . '/app');
define('SJO_ROOT_MODEL', SJO_ROOT_APP . '/Model');
define('SJO_ROOT_VIEW', SJO_ROOT_APP . '/View');
define('SJO_ROOT_CONTROLLER', SJO_ROOT_APP . '/Controller');
define('SJO_ROOT_PUBLIC_HTML', SJO_ROOT . '/public_html');
define('SJO_ROOT_TMP', '/tmp');
define('SJO_ROOT_LOG', SJO_ROOT . '/log');

define('SJO_DB_HOST', 'localhost');
define('SJO_DB_USER', 'root');
define('SJO_DB_PWD', '');
define('SJO_DB_BASE', '');

define('SJO_CONTROLLER_NAME', 'controller');
define('SJO_CONTROLLER_DEFAULT', 'Home');

define('SJO_METHOD_NAME', 'method');
define('SJO_METHOD_DEFAULT', '');

define('SJO_CONTROLLER_METHOD_SEPARATOR', '::');
define('SJO_BASEHREF', './');

define('SJO_SALT', '1eecf9f2251f9ec8468a67df25154cb9');
