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

namespace PHPTools;

abstract class Arr
{
    public static function getTree($array, $tree, $value = false)
    {
        if(is_object($array)) {
            $array = (array) $array;
        }
        $tree = is_array($tree) && count($tree) == 1 ? $tree[0] : $tree;
        if (is_array($tree)) {
            $optional = false;
            $name = array_shift($tree);
            if(preg_match("#^\((.+)\)$#", $name, $match)) {
                $name = $match[1];
                $optional = true;
            }
            if (isset($array[$name])) {
                return self::getTree($array[$name], $tree, $value);
            } elseif($optional) {
                return self::getTree($array, $tree, $value);
            }
        } else {
            $name = $tree;
            if (isset($array[$name])) {
                return $array[$name];
            }
        }
        return false;
    }

    /**
     * Remplacement des espaces
     */
    public static function explode($sep, $array)
    {
        if ($array) {
            return array_map('trim', explode($sep, $array));
        }
        return false;
    }

    /**
     * Convertit une chaine en tableau
     */
    public static function to($array)
    {
        if (!is_array($array)) {
            return array($array);
        }
        return $array;
    }
    
    public static function toObject(&$array)
    {
        foreach($array as &$item) {
            $item = (object) $item;
        }        
    }    

    /**
     * Tri un tableau
     */
    public static function sort(&$array, $key, $order = SORT_DESC)
    {
        if($array) {
            $tmp = array();
            foreach($array as $item) {
                $tmp[] = $item[$key];
            }
            array_multisort($tmp, $order, $array);
        }
    }    
}
