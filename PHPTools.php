<?php
/**
 * phpTools
 *
 * PHP version 5
 *
 * @package  phpTools
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/phpTools
 */
 
/** Compatibilité anciennes méthodes */
include realpath(__DIR__) . '/__deprecated.php';

/** Configuration */
defined('PHPTOOLS_CHARSET') OR define('PHPTOOLS_CHARSET', 'UTF-8');

defined('PHPTOOLS_ROOT_APP') OR define('PHPTOOLS_ROOT_MODEL', 'App');
defined('PHPTOOLS_ROOT_MODEL') OR define('PHPTOOLS_ROOT_MODEL', PHPTOOLS_ROOT_APP . '/Model');
defined('PHPTOOLS_ROOT_VIEW') OR define('PHPTOOLS_ROOT_VIEW', PHPTOOLS_ROOT_APP . '/View');
defined('PHPTOOLS_ROOT_CONTROLLER') OR define('PHPTOOLS_ROOT_CONTROLLER', PHPTOOLS_ROOT_APP . '/Controller');

defined('PHPTOOLS_DB_HOST') OR define('PHPTOOLS_DB_HOST', 'localhost');
defined('PHPTOOLS_DB_USER') OR define('PHPTOOLS_DB_USER', 'root');
defined('PHPTOOLS_DB_PWD') OR define('PHPTOOLS_DB_PWD', '');
defined('PHPTOOLS_DB_BASE') OR define('PHPTOOLS_DB_BASE', '');

defined('PHPTOOLS_CONTROLLER_NAME') OR define('PHPTOOLS_CONTROLLER_NAME', 'controller');
defined('PHPTOOLS_CONTROLLER_DEFAULT') OR define('PHPTOOLS_CONTROLLER_DEFAULT', 'Home');
defined('PHPTOOLS_CONTROLLER_AUTH') OR define('PHPTOOLS_CONTROLLER_AUTH', 'Auth');
defined('PHPTOOLS_METHOD_NAME') OR define('PHPTOOLS_METHOD_NAME', 'method');
defined('PHPTOOLS_METHOD_DEFAULT') OR define('PHPTOOLS_METHOD_DEFAULT', '');

defined('PHPTOOLS_CONTROLLER_METHOD_SEPARATOR') OR define('PHPTOOLS_CONTROLLER_METHOD_SEPARATOR', '::');
defined('PHPTOOLS_FULL_RESTRICTED_ACCESS') OR define('PHPTOOLS_FULL_RESTRICTED_ACCESS', false);

defined('PHPTOOLS_SALT') OR define('PHPTOOLS_SALT', '1eecf9f2251f9ec8468a67df25154cb9');

/** Classes */
spl_autoload_register(
    function ($class) {
        $filename = str_replace('\\', '/', $class) . '.php';
        if (strstr($filename, 'PHPTools/')) {
            $filename = str_replace('PHPTools/', '', $filename);
            if (!strstr($filename, '/')) {
                include realpath(__DIR__) . '/Core/' . $filename;
            } else {
                include realpath(__DIR__) . '/' . $filename;
            }
        }
    }
);

/** Helpers */
foreach(\PHPTools\Libraries\Dir::getFiles(realpath(__DIR__) . '/Helpers') as $helpers) {
	include $helpers->path;
}
