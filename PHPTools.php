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

defined('PHPTOOLS_CHARSET') OR define('PHPTOOLS_CHARSET', 'UTF-8');
defined('PHPTOOLS_ROOT_TPL') OR define('PHPTOOLS_ROOT_TPL', 'tpl/');
defined('PHPTOOLS_DB_HOST') OR define('PHPTOOLS_DB_HOST', 'localhost');
defined('PHPTOOLS_DB_USER') OR define('PHPTOOLS_DB_USER', 'root');
defined('PHPTOOLS_DB_PWD') OR define('PHPTOOLS_DB_PWD', '');
defined('PHPTOOLS_DB_BASE') OR define('PHPTOOLS_DB_BASE', '');

spl_autoload_register(
    function ($className) {
        if (strstr($className, 'PHPTools\\')) {
            require realpath(__DIR__) . '/class/' . str_replace('PHPTools\\', '', $className) . '.php';
        }
    }
);
