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

use sJo\Exception\Exception;
use sJo\Libraries\I18n;

abstract class File
{
    /**
     * @param $src
     * @param $dest
     *
     * @throws \sJo\Exception\Exception
     * @return bool
     */
    public static function copy($src, $dest)
    {
        if(!copy($src, $dest)) {

            throw new Exception(null, I18n::__('Unable to copy file %s to %s', $src, $dest));
        }

        return true;
    }

    /**
     * @param     $file
     * @param     $content
     * @param int $options
     *
     * @return int
     * @throws \sJo\Exception\Exception
     */
    public static function write($file, $content, $options = LOCK_EX)
    {
        if(!is_writable(Path::parent($file))) {

            throw new Exception(I18n::__('Unable to write to file %s', $file));
        }

        return file_put_contents($file, $content, $options);
    }

    /**
     * @param $file
     * @param $content
     *
     * @return int
     */
    public static function append($file, $content)
    {
        return self::write($file, $content, FILE_APPEND | LOCK_EX);
    }

    /**
     * @param        $path
     * @param string $return
     *
     * @return bool|int
     */
    public static function lastModified($path, $return = 'mtime')
    {
        $file   = false;
        $mtime  = 0;

        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path)) as $fileinfo) {

            if ($fileinfo->isFile()) {

                if ($fileinfo->getMTime() > $mtime) {

                    $file   = $fileinfo->getFilename();
                    $mtime  = $fileinfo->getMTime();
                }
            }
        }

        switch($return) {

            case 'mtime':
                return $mtime;
                break;

            case 'file':
                return $file;
                break;
        }

        return false;
    }

    /**
     * @param $file
     *
     * @throws \sJo\Exception\Exception
     */
    public static function __require($file)
    {
        if(!file_exists($file)) {

            throw new Exception(I18n::__('File %s do not exists', $file));
        }

        require $file;
    }

    /**
     * @param $file
     */
    public static function __include($file)
    {
        if(file_exists($file)) {

            require $file;
        }
    }
}
