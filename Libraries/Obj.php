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
    
    public static function toArray(&$obj)
    {
        foreach($obj as &$item) {
            $item = (array) $item;
        }        
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
