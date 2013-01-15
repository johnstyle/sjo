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

abstract class Dir
{
    public static function parent($path)
    {
        return preg_replace("#/[^/]+(/)?$#", "$1", $path);
    }

    public static function create($path, $mode = 0755)
    {
        if (!is_dir($path)) {
            mkdir($path, $mode);
        }
        return $path;
    }

    public static function getFiles($path, $regexp = false, $limit = false)
    {
        return self::open('is_dir', $path, $regexp, $limit);
    }

    public static function gets($path, $regexp = false, $limit = false)
    {
        return self::open('is_file', $path, $regexp, $limit);
    }
    
    private static function open($type, $path, $regexp = false, $limit = false)
    {
        $i = 0;
        $items = false;
        if (is_dir($path)) {
            if ($dir = opendir($path)) {
                while ($file = readdir($dir)) {
                    $match = false;
                    if (
                           $file != '..'
                        && $file != '.'
                        && $type($path . '/' . $file)
                        && (!$regexp || ($regexp && preg_match($regexp, $file, $match)))
                    ) {
                        $items[] = (object) array(
                            'title' => Str::beautify($file),
                            'name'  => $file,
                            'dir'   => $path,
                            'path'  => $path . '/' . $file,
                            'match' => $match
                        );
                        if ($limit && $i >= $limit) {
                            break;
                        }
                        $i++;
                    }
                }
            }
        }
        return $items;
    }
}