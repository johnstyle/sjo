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

abstract class Obj
{
    /**
     * Merge intelligent d'un tableau d'objets
     *
     * @param \stdClass $obj
     * @param \stdClass $obj2
     * @return \stdClass
     */
    public static function extend(\stdClass $obj, \stdClass $obj2)
    {
        self::toArray($obj);
        self::toArray($obj2);

        $obj = Arr::extend($obj, $obj2);
        Arr::toObject($obj);

        return $obj;
    }

    /**
     * Transforme un tableau d'objet en array
     *
     * @param \stdClass $obj
     * @return array
     */
    public static function toArray(&$obj)
    {
        if(is_object($obj) || is_array($obj)) {
            $obj = (array) $obj;
            foreach($obj as &$item) {
                if(is_scalar($item)) {
                    self::toArray($item);
                }
            }
        }
    }


    public static function tree(&$obj, $tree, $value = false)
    {
        $tree = is_array($tree) && count($tree) == 1 ? $tree[0] : $tree;
        if (is_array($tree)) {
            self::tree($obj->{array_shift($tree)}, $tree, $value);
        } else {
            $obj->{$tree} = $value;
        }
        return $obj;
    }

    /**
     * Tri un tableau
     */
    public static function sort(&$obj, $key, $order = SORT_DESC)
    {
        if($obj) {
            self::toArray($obj);
            Arr::sort($obj, $key, $order);
            Arr::toObject($obj);
        }     
    }    
}
