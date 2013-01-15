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

spl_autoload_register(function($className) {
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    $path = str_replace('phpTools/', '', $path);
    require 'class/' . $path . '.class.php';
});

defined('PHPTOOLS_CHARSET')  OR define('PHPTOOLS_CHARSET', 'UTF-8');
defined('PHPTOOLS_ROOT_TPL') OR define('PHPTOOLS_ROOT_TPL', 'tpl/');

defined('PHPTOOLS_DB_HOST')  OR define('PHPTOOLS_DB_HOST', 'localhost');
defined('PHPTOOLS_DB_USER')  OR define('PHPTOOLS_DB_USER', 'root');
defined('PHPTOOLS_DB_PWD')   OR define('PHPTOOLS_DB_PWD', '');
defined('PHPTOOLS_DB_BASE')  OR define('PHPTOOLS_DB_BASE', '');

