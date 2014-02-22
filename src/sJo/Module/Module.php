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
use sJo\Loader\Router;
use sJo\Object\Event;
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

    public static $root;
    private static $loadedModules = array();

    public function __construct()
    {
        self::$root = realpath(dirname(__FILE__));

        if(count(self::$loadedModules)) {

            foreach(self::$loadedModules as $module=>$use) {

                if ($use) {

                    $loaderClass = '\\sJo\\Module\\' . $module . '\\Loader';
                    $bootstrapFile = self::$root . '/' . $module . (Router::$interface ? '/' . Router::$interface : '') . '/bootstrap.php';

                    /** Loader */
                    if(class_exists($loaderClass)) {
                        $this->instance = new $loaderClass (new Component());
                        $this->event('init');
                    }

                    /** Bootstrap */
                    Lib\File::__include($bootstrapFile);
                }
            }
        }
    }

    public static function load($module)
    {
        self::$loadedModules[$module] = true;
    }

    public static function loaded($module)
    {
        return isset(self::$loadedModules[$module])
            && self::$loadedModules[$module] === true;
    }
}
