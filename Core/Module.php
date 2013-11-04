<?php

/**
 * Gestion des modules
 *
 * PHP version 5
 *
 * @package  PHPTools
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/PHPTools.git
 */

namespace PHPTools;

/**
 * Gestion des modules
 *
 * @package  PHPTools
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/PHPTools.git
 */
class Module
{
    public static function getClassName($type)
    {
        if(Loader::$module) {
            return '\\PHPTools\\Modules\\' . Loader::$module . '\\' . $type . '\\' . Loader::$controller;
        } else {
            return '\\' . $type . '\\' . Loader::$controller;
        }
    }

    public static function getView($filename)
    {
        if(Loader::$module) {
            return realpath(__DIR__) . '/' . PHPTOOLS_ROOT . '/Modules/' . Loader::$module . '/View/' . $filename . '.php';
        } else {
            return PHPTOOLS_ROOT_VIEW . '/' . $filename . '.php';
        }
    }
}
