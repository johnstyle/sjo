<?php

/**
 * Gestion des Dépendances
 *
 * PHP version 5
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */

namespace sJo\Core;

use sJo\Libraries as Lib;

/**
 * Gestion des Dépendances
 */
class Dependencies
{
    private static $requiredClasses = array();

    public static function check($classes)
    {
        foreach($classes as $class) {
            if(!in_array($class, self::$requiredClasses)) {
                Exception::error(Lib\I18n::__('Class %s is required.', $class));
            }
        }
    }

    public static function register($class)
    {
        self::$requiredClasses[] = $class;
    }
}