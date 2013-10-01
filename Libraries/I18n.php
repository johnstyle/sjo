<?php

/**
 * PHPTools
 *
 * PHP version 5
 *
 * @package  PHPTools
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/PHPTools.git
 */

namespace PHPTools\Libraries;

abstract class I18n {

    public static function gettext($original) {
        
    }

    public static function ngettext($original, $plural, $value) {
        
    }

    public static function pgettext($context, $original) {
        
    }

    public static function replace($text, $args, $start = 0) {
        if (func_num_args() === $start) {
            return $text;
        }        
        $args = array_slice($args, $start);
        return vsprintf($text, is_array($args[0]) ? $args[0] : $args);
    }

}
