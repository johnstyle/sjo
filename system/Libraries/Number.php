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

abstract class Number
{
    public static function percent($val1, $val2){
        $percent = '-';
        if($val1) {
            $diff = round((($val1 - $val2) / $val1) * 100, 2);
            if($diff > 0) {
                $percent = '+'.$diff;
            } else {
                $percent = $diff;
            }
            $percent .= '%';
        }
        return $percent;
    }
}
