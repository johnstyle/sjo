<?php

namespace sJo\Core;

use sJo\Libraries as Lib;

class Router
{
    public static $interface;
    public static $controller;
    public static $controllerClass;
    public static $viewRoot;
    public static $viewFile;
    public static $method;
    public static $module;

    private function _set($type, $value = false)
    {
        switch($type) {
            case 'interface':
                self::$interface = $value;
                if (!self::$interface) {
                    self::$interface = Lib\Env::get(SJO_INTERFACE_NAME, SJO_INTERFACE_DEFAULT);
                }
                break;
            case 'controller':
                self::$controller = $value;
                if (!self::$controller) {
                    self::$controller = Lib\Env::request(SJO_CONTROLLER_NAME, SJO_CONTROLLER_DEFAULT);
                    self::$controller = trim(self::$controller, '/');
                    self::$controller = str_replace('/', '\\', self::$controller);
                }
                self::$controllerClass = '\\' . self::$interface . '\\Controller\\' . Loader::$controller;
                self::$viewRoot = SJO_ROOT_APP . '/' . self::$interface . '/View';
                self::$viewFile = self::$viewRoot . '/' . str_replace('\\', '/', self::$controller) . '.php';
                break;
            case 'method':
                self::$method = $value;
                if (!self::$method) {
                    self::$method = Lib\Env::request(SJO_METHOD_NAME, SJO_METHOD_DEFAULT);
                }
                break;
        }
    }
}
