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

namespace sJo\Libraries;

use sJo\Core;

abstract class File
{
    public static function write($file, $content, $options = LOCK_EX)
    {
        if(is_writable(Path::parent($file))) {
            return file_put_contents($file, $content, $options);
        } else {
            Core\Exception::error(I18n::__('Unable to write to file %s', $file));
        }

        return false;
    }

    public static function append($file, $content)
    {
        return self::write($file, $content, FILE_APPEND | LOCK_EX);
    }

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

    public static function __require($file)
    {
        if(file_exists($file)) {
            require $file;
        } else {
            Core\Exception::error(I18n::__('File %s do not exists', $file));
        }
    }

    public static function __include($file)
    {
        if(file_exists($file)) {
            require $file;
        }
    }
}
