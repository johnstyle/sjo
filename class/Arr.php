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
    public static function getTree ($array, $items = false)
    {
        if (is_object($array)) {
            $array = (array)$array;
        }
        if (!is_array($items) && strstr($items, '.')) {
            $items = explode('.', $items);
        }
        $items = is_array($items) && count($items) == 1 ? $items[0] : $items;
        if (!$items) {
            return $array;
        } elseif (is_array($items)) {
            $optional = false;
            $name = array_shift($items);
            if (preg_match("#^\((.+)\)$#", $name, $match)) {
                $name = $match[1];
                $optional = true;
            }
            if (isset($array[$name]) && $array[$name] !== false) {
                return self::getTree($array[$name], $items);
            } elseif ($optional) {
                return self::getTree($array, $items);
            }
        } else {
            $name = $items;
            if (isset($array[$name]) && $array[$name] !== false) {
                return $array[$name];
            }
        }
        return false;
    }

    public static function setTree (&$array, $items)
    {
        if ($items !== false) {
            if (is_array($items)) {
                foreach ($items as $key => $item) {
                    if (!is_int($key) || max(array_keys($items)) != (count($items) - 1)) {
                        if (isset($array[$key])) {
                            self::setTree($array[$key], $item);
                        } else {
                            $array[$key] = $item;
                        }
                    } else {
                        $array[] = $item;
                    }
                }
            } else {
                $array = $items;
            }
        }
        return $array;
    }

    /**
     * Remplacement des espaces
     */
    public static function explode ($sep, $array)
    {
        if ($array) {
            return array_map('trim', explode($sep, $array));
        }
        return false;
    }

    /**
     * Convertit une chaine en tableau
     */
    public static function to ($array)
    {
        if (!is_array($array)) {
            return array($array);
        }
        return $array;
    }

    public static function toObject (&$array)
    {
        foreach ($array as &$item) {
            $item = (object)$item;
        }
    }

    /**
     * Tri un tableau
     */
    public static function sort (&$array, $key, $order = SORT_DESC)
    {
        if ($array) {
            $tmp = array();
            foreach ($array as $item) {
                $tmp[] = $item[$key];
            }
            array_multisort($tmp, $order, $array);
        }
    }

}
