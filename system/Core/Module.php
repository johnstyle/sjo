<?php

/**
 * Module
 *
 * PHP version 5
 *
 * @package  PHPTools
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/PHPTools.git
 */

namespace PHPTools;

/**
 * Module
 *
 * @package  PHPTools
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/PHPTools.git
 */
trait Module
{
    private $modules = array();

    public function useModule($module)
    {
        if(!in_array($module, $this->modules)) {
            $this->modules[] = $module;
        }
    }

    private function _setModule()
    {
        $controller = '\\Controller\\' . self::$controller;
        if(!class_exists($controller) && preg_match("#^(.+?)\\\\([^\\\\]+)$#", self::$controller, $match)) {
            if(in_array($match[1], $this->modules)) {
                self::$module = $match[1];
                self::$controllerClass = '\\PHPTools\\Modules\\' . self::$module . '\\Controller\\' . $match[2];
                self::$modelClass = '\\PHPTools\\Modules\\' . self::$module . '\\Model\\' . $match[2];
                self::$viewFile = realpath(__DIR__) . '/' . PHPTOOLS_ROOT . '/Modules/' . self::$module . '/View/' . str_replace('\\', '/', $match[2]) . '.php';
            }
        }
    }

    private function _loadModules()
    {
        if(count($this->modules)) {
            foreach($this->modules as $module) {
                $className = '\\PHPTools\\Modules\\' . $module . '\\Loader';
                if(class_exists($className)) {
                    $Loader = new $className ($this->instance);
                    if(method_exists($className, 'init')) {
                        $this->instance->Module->{$module} = $Loader->init();
                    }
                }
            }
        }
    }
}
