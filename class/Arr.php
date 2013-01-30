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

namespace phpTools;

abstract class Arr
{
    /**
     * Remplacement des espaces
     */
    public static function explode($sep, $array)
    {
        if($array) {
            return array_map('trim', explode($sep, $array));
        }
        return false;
    }
}
