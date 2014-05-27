<?php

namespace sJo\Loader;

use sJo\Exception\Exception;
use sJo\Libraries as Lib;
use sJo\Module\Module;
use sJo\Request\Request;

/**
 * Class Router
 * @package sJo\Loader
 *
 * @method static linkBack()
 * @method static linkFront()
 */
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
            'default' => 'index'
        )
    );

    public static $interface;
    public static $controller;
    public static $controllerClass;
    public static $viewRoot;
    public static $viewFile;
    public static $method;
    public static $module;

    public function __construct()
    {
        /** Interface */
        self::loadInterface();

        /** Controller */
        self::loadController();

        /** View */
        self::loadView();

        /** Method */
        self::loadMethod();

        /** Module */
        self::loadModule();
    }

    public static function __callStatic($method, array $args = null)
    {
        if (preg_match("#^(link)([A-Z][a-z]+)$#", $method, $match)) {
            switch ($match[1]) {
                case 'link':
                    return call_user_func_array('self::link', array_merge(array($match[2]), $args));
                    break;
            }
        }

        Exception::error(Lib\I18n::__('Unknow method %s', __CLASS__ . '::' . $method));
        return false;
    }

    public static function defaultInterface($interface)
    {
        self::$__map['interface']['default'] = $interface;
    }

    public static function defaultController($controller)
    {
        self::$__map['controller']['default'] = $controller;
    }

    private static function loadInterface()
    {
        self::$interface = Request::env('GET')->{self::$__map['interface']['name']}->val(self::$__map['interface']['default'], true);
    }

    private static function loadController()
    {
        self::$controller = Request::env('REQUEST')->{self::$__map['controller']['name']}->val(self::$__map['controller']['default'], true);
        self::$controller = trim(self::$controller, '/');
        self::$controller = str_replace('/', '\\', self::$controller);
        self::$controllerClass = '\\' . self::$interface . '\\Controller\\' . self::$controller;
    }

    private static function loadView()
    {
        self::$viewRoot = SJO_ROOT_APP . '/' . self::$interface . '/View';
        self::$viewFile = self::$viewRoot . '/' . str_replace('\\', '/', self::$controller) . '.php';
    }

    private static function loadMethod()
    {
        self::$method = Request::env('REQUEST')->{self::$__map['method']['name']}->val(self::$__map['method']['default'], true);
    }

    private static function loadModule()
    {
        if(!class_exists(self::$controllerClass)
            && preg_match("#^(.+?)\\\\([^\\\\]+)$#", self::$controller, $match)) {
            if (Module::loaded($match[1])) {
                self::$module = $match[1];
                self::$controllerClass = Module::getClass(self::$module, self::$interface . '\\Controller\\' . $match[2]);
                self::$viewFile = Module::getFile(self::$module, self::$interface . '/View/' . str_replace('\\', '/', $match[2]) . '.php');
            }
        }
    }

    public static function errorDocument($controller)
    {
        self::reset();
        self::$controller = 'ErrorDocument\\' . $controller;
        self::loadModule();
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

    public static function link($interface = null, $controller = null, array $params = null)
    {
        $params = Lib\Arr::extend(array(
            'method' => null
        ), $params);

        if ($controller === null) {
            $controller = self::getToken($params['method']);
            unset($params['method']);
        }

        $params = http_build_query($params, '/?');

        return SJO_BASEHREF
            . ($interface && self::$__map['interface']['default'] != $interface ? '/' . $interface : '')
            . ($controller ? '/' . str_replace('\\', '/', $controller) : '')
            . ($params ? '/?' . $params : '');
    }

    public static function getToken($method = null, $form = null)
    {
        $method = $method ? $method : self::$method;

        return self::$controller
            . ($method ? self::$__map['method']['separator'] . $method : '')
            . ($form ? '(' . $form . ')' : '');
    }
}
