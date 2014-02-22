<?php

namespace sJo\Loader;

use sJo\Libraries as Lib;
use sJo\Module\Module;

class Router
{
    public static $__map = array(
        'interface' => array(
            'name' => 'interface',
            'default' => 'Front'
        ),
        'controller' => array(
            'name' => 'controller',
            'default' => 'Home'
        ),
        'method' => array(
            'name' => 'method',
            'separator' => '::',
            'default' => null
        )
    );

    public static $interface;
    public static $controller;
    public static $controllerClass;
    public static $viewRoot;
    public static $viewFile;
    public static $method;
    public static $module;

    public function __construct(Loader $loader)
    {
        /** Interface */
        self::setInterface();

        /** Controller */
        self::setController();

        /** View */
        self::setView();

        /** Method */
        self::setMethod();

        /** Module */
        self::setModule();
    }

    private static function reset()
    {
        self::$interface = null;
        self::$controller = null;
        self::$controllerClass = null;
        self::$viewRoot = null;
        self::$viewFile = null;
        self::$method = null;
        self::$module = null;
    }

    public static function ErrorDocument($controller)
    {
        self::reset();
        self::$controller = 'ErrorDocument\\' . $controller;
        self::setModule();
    }

    private static function setInterface()
    {
        self::$interface = Lib\Env::get(self::$__map['interface']['name'], self::$__map['interface']['default']);
    }

    private static function setController()
    {
        self::$controller = Lib\Env::request(self::$__map['controller']['name'], self::$__map['controller']['default']);
        self::$controller = trim(self::$controller, '/');
        self::$controller = str_replace('/', '\\', self::$controller);
        self::$controllerClass = '\\' . self::$interface . '\\Controller\\' . self::$controller;
    }

    private static function setView()
    {
        self::$viewRoot = SJO_ROOT_APP . '/' . self::$interface . '/View';
        self::$viewFile = self::$viewRoot . '/' . str_replace('\\', '/', self::$controller) . '.php';
    }

    private static function setMethod()
    {
        self::$method = Lib\Env::request(self::$__map['method']['name'], self::$__map['method']['default']);
    }

    private static function setModule()
    {
        if(!class_exists(self::$controllerClass)
            && preg_match("#^(.+?)\\\\([^\\\\]+)$#", self::$controller, $match)) {
            if (Module::loaded($match[1])) {
                self::$module = $match[1];
                self::$controllerClass = '\\sJo\\Module\\' . self::$module . (self::$interface ? '\\' . self::$interface : '') . '\\Controller\\' . $match[2];
                self::$viewFile = realpath(__DIR__) . '/' . SJO_ROOT . '/Module/' . self::$module . (self::$interface ? '/' . self::$interface : '') . '/View/' . str_replace('\\', '/', $match[2]) . '.php';
            }
        }
    }
}
