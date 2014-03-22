<?php

/**
 * Module
 *
 * PHP version 5
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */

namespace sJo\Module;

use sJo\Controller\Component\Component;
use sJo\Exception\Exception;
use sJo\Loader\Router;
use sJo\Model\Event;
use sJo\Libraries as Lib;

/**
 * Module
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */
class Module
{
    use Event;

    private static $loadedModules = array();

    public function __construct()
    {
        if(count(self::$loadedModules)) {

            foreach(self::$loadedModules as $module=>$option) {

                if ($option['use'] === true) {

                    /** Loader */
                    if($loaderClass = self::getClass($module, 'Loader')) {
                        $this->instance = new $loaderClass (new Component());
                        $this->event('init');
                    }

                    /** Bootstrap */
                    if ($bootstrapFile = self::getFile($module, Router::$interface . '/bootstrap.php')) {
                        Lib\File::__include($bootstrapFile);
                    }
                }
            }
        }
    }

    public static function getRoot($file = null)
    {
        return realpath(dirname(__FILE__)) . $file;
    }

    public static function getClass($module, $className)
    {
        $class = '\\Module\\' . $module . '\\' . ltrim($className, '\\');
        $defaultClass = '\\sJo' . $class;

        if (class_exists($class)) {
            if (!get_parent_class($class) == $defaultClass) {
                Exception::error(Lib\I18n::__('Classe %s is not extended to %s.', $class, $defaultClass));
            }
        } else {
            $class = $defaultClass;
        }

        if (class_exists($class)) {
            return $class;
        }

        return false;
    }

    public static function getFile($module, $fileName)
    {
        $file = $module . '/' . ltrim($fileName, '/');
        if (file_exists(SJO_ROOT_APP . '/Module/' . $file)) {
            $file = SJO_ROOT_APP . '/Module/' . $file;
        } else {
            $file = self::getRoot('/' . $file);
        }

        if (file_exists($file)) {
            return $file;
        }

        return false;
    }

    public static function load($module)
    {
        self::$loadedModules[$module] = array(
            'use' => true
        );
    }

    public static function loaded($module)
    {
        return isset(self::$loadedModules[$module])
            && self::$loadedModules[$module]['use'] === true;
    }
}
