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

 /** Configuration */
defined('PHPTOOLS_CHARSET') OR define('PHPTOOLS_CHARSET', 'UTF-8');
defined('PHPTOOLS_ROOT_TPL') OR define('PHPTOOLS_ROOT_TPL', 'tpl/');
defined('PHPTOOLS_DB_HOST') OR define('PHPTOOLS_DB_HOST', 'localhost');
defined('PHPTOOLS_DB_USER') OR define('PHPTOOLS_DB_USER', 'root');
defined('PHPTOOLS_DB_PWD') OR define('PHPTOOLS_DB_PWD', '');
defined('PHPTOOLS_DB_BASE') OR define('PHPTOOLS_DB_BASE', '');

/** Classes */
spl_autoload_register(
    function ($className) {
        if (strstr($className, 'PHPTools\\')) {
            $className = str_replace('PHPTools\\', '', $className);
            $className = str_replace('\\', '/', $className);
            require realpath(__DIR__) . '/class/' . $className . '.php';
        }
    }
);

/** Helpers */
foreach(PHPTools\Dir::getFiles(realpath(__DIR__) . '/Helpers') as $helpers) {
	include $helpers->path;
}
