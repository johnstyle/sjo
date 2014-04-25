<?php
/**
 * sJo
 *
 * PHP version 5
 *
 * @package  sJo
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */

namespace sJo\File;

abstract class Path
{
    /**
     * @param $path
     *
     * @return mixed
     */
    public static function parent($path)
    {
        return preg_replace("#/[^/]+(/)?$#", "$1", $path);
    }

    /**
     * @param      $path
     * @param int  $mode
     * @param bool $recursive
     *
     * @return mixed
     */
    public static function create($path, $mode = 0755, $recursive = false)
    {
        if (!is_dir($path)) {

            mkdir($path, $mode, $recursive);
        }

        return $path;
    }

    /**
     * @param      $path
     * @param bool $regexp
     * @param bool $limit
     *
     * @return array|bool
     */
    public static function listFiles($path, $regexp = false, $limit = false)
    {
        return self::open('is_file', $path, $regexp, $limit);
    }

    /**
     * @param      $path
     * @param bool $regexp
     * @param bool $limit
     *
     * @return array|bool
     */
    public static function listDirectories($path, $regexp = false, $limit = false)
    {
        return self::open('is_dir', $path, $regexp, $limit);
    }

    /**
     * @param      $type
     * @param      $path
     * @param bool $regexp
     * @param bool $limit
     *
     * @return array|bool
     */
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
                        && (!$regexp || ($regexp && preg_match("#" . $regexp . "#", $file, $match)))
                    ) {

                        $title = $file;

                        if($type == 'is_file') {

                            $title = substr($file, 0, strpos($file, '.'));
                        }

                        $items[] = (object) array(
                            'title'         => $title,
                            'name'          => $file,
                            'parentname'    => basename($path),
                            'dir'           => $path,
                            'path'          => $path . '/' . $file,
                            'match'         => $match
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
