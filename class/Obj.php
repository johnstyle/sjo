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
}
