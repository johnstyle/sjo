<?php
/**
 * phpTools
 *
 * PHP version 5
 *
 * @package  PHPTools
 * @category Core 
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/phpTools
 */

/** Configuration */
defined('PHPTOOLS_DEBUG') OR define('PHPTOOLS_DEBUG', false);

defined('PHPTOOLS_CHARSET') OR define('PHPTOOLS_CHARSET', 'UTF-8');
defined('PHPTOOLS_DEFAULT_LOCALE') OR define('PHPTOOLS_DEFAULT_LOCALE', 'en_US');

defined('PHPTOOLS_ROOT') OR define('PHPTOOLS_ROOT', '..');
defined('PHPTOOLS_ROOT_APP') OR define('PHPTOOLS_ROOT_APP', PHPTOOLS_ROOT . '/App');
defined('PHPTOOLS_ROOT_MODEL') OR define('PHPTOOLS_ROOT_MODEL', PHPTOOLS_ROOT_APP . '/Model');
defined('PHPTOOLS_ROOT_VIEW') OR define('PHPTOOLS_ROOT_VIEW', PHPTOOLS_ROOT_APP . '/View');
defined('PHPTOOLS_ROOT_CONTROLLER') OR define('PHPTOOLS_ROOT_CONTROLLER', PHPTOOLS_ROOT_APP . '/Controller');
defined('PHPTOOLS_ROOT_PUBLIC_HTML') OR define('PHPTOOLS_ROOT_PUBLIC_HTML', PHPTOOLS_ROOT . '/public_html');
defined('PHPTOOLS_ROOT_TMP') OR define('PHPTOOLS_ROOT_TMP', sys_get_temp_dir());
defined('PHPTOOLS_ROOT_LOG') OR define('PHPTOOLS_ROOT_LOG', PHPTOOLS_ROOT . '/log');

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

defined('PHPTOOLS_BASEHREF') OR define('PHPTOOLS_BASEHREF', './');

defined('PHPTOOLS_SALT') OR define('PHPTOOLS_SALT', '1eecf9f2251f9ec8468a67df25154cb9');

defined('PHPTOOLS_TIMEZONE') OR define('PHPTOOLS_TIMEZONE', 'Europe/Paris');

date_default_timezone_set(PHPTOOLS_TIMEZONE);

/** Classes */
spl_autoload_register(
    function ($class) {
        $filename = str_replace('\\', '/', $class) . '.php';
        if (strstr($filename, 'PHPTools/')) {
            $filename = str_replace('PHPTools/', '', $filename);
            if (!strstr($filename, '/')) {
                $file = realpath(__DIR__) . '/Core/' . $filename;
                if(file_exists($file)) {
                    include $file;
                }
            } else {
                $file = realpath(__DIR__) . '/' . $filename;
                if(file_exists($file)) {
                    include $file;
                }
            }
        }
    }
);

/** Helpers */
foreach(\PHPTools\Libraries\Dir::getFiles(realpath(__DIR__) . '/Helpers') as $helpers) {
    include $helpers->path;
}

/** Locale */
//$PHPToolsI18n = new \PHPTools\Libraries\I18n();
//$PHPToolsI18n->load('default', realpath(__DIR__) . '/Locale');
