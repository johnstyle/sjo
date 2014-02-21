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

use sJo\Controller\Controller;
use sJo\Object\Event;

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

            foreach(self::$loadedModules as $module=>$use) {

                if ($use) {

                    $className = '\\sJo\\Module\\' . $module . '\\Loader';

                    if(class_exists($className)) {
                        $this->instance = new $className (new Controller());
                        $this->event('init');
                    }
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
