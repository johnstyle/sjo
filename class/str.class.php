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

abstract class str
{

    /**
     * Correction des données d'environement
     */
    public static function fromEnv($str)
    {
        if (is_array($str)) {
            $str = array_map(array('str', 'fromEnv'), $str);
        } elseif (is_object($str)) {
            $str = (object)array_map(array('str', 'fromEnv'), (array)$str);
        } else {
            $str = self::stripslashes($str);
        }
        return $str;
    }

    /**
     * Stripslashes limité aux quotes
     */
    public static function stripslashes($str)
    {
        $str = str_replace('\"', '"', $str);
        $str = str_replace("\'", "'", $str);
        return $str;
    }

    /**
     * Remplacement des espaces
     */
    public static function noBlank($str)
    {
        return str::trim(preg_replace("#(\n|\r|\s|\t|&nbsp;)+#i", " ", $str));
    }

    /**
     * Remplacement des saut de lignes
     */
    public static function noBreak($str)
    {
        return str::trim(preg_replace("#(\s|\n|<br[\s]*/?>)+#", "\n", $str), ' -.:,/');
    }

    /**
     * Formatage pour une expression régulière
     */
    public static function toRegexp($str, $blank = true)
    {
        $str = str_replace(
            array('-', '?', '*', '+', '^', '$', '#', '.', '(', ')', '[', ']', '{', '}'),
            array('\-', '\?', '\*', '\+', '\^', '\\$', '\#', '\.', '\(', '\)', '\[', '\]', '\{', '\}'),
            $str
        );
        if ($blank) {
            $str = preg_replace("#[\s]+#s", '[\s]+', $str);
        }
        return $str;
    }
}
