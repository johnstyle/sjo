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

abstract class Ini
{
    /**
     * Tableau des valeurs des fichiers .ini
     *
     * @var private
     */
    private static $data;

    public static function __callStatic($method, $args = false)
    {
        return self::_get($method, $args);
    }

    /**
     * Chargement des fichiers .ini
     *
     * @param string Chemin des fichiers .ini
     * @return void
     */
    public static function load($paths)
    {
        foreach (Arr::to($paths) as $path) {
            $files = Dir::getFiles($path, "#^(.+)\.ini$#");
            if ($files) {
                foreach ($files as $file) {
                    self::$data[$file->title] = parse_ini_file($file->path, true);
                }
            }
        }
    }

    /**
     * Retourne une valeur
     *
     * @return string
     */
    private static function _get($method, $args = false)
    {
        if (isset(self::$data[$method])) {
            return Arr::getTree(self::$data[$method], $args);
        }
        return false;
    }
}
