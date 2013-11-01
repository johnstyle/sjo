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
    public function getClassName($type, $module, $controller)
    {
        if($module) {
            return '\\PHPTools\\Modules\\' . $module . '\\' . $type . '\\' . $controller;
        } else {
            return '\\' . $type . '\\' . $controller;
        }
    }
}
