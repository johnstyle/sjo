<?php

/**
 * Loader
 *
 * PHP version 5
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */

namespace sJo\Core;

use sJo\Libraries as Lib;
use sJo\Helpers;

/**
 * Loader
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */
class Loader
{
    use Module;

    private $root;
    private $instance;
    public static $interface;
    public static $controller;
    public static $controllerClass;
    public static $viewRoot;
    public static $viewFile;
    public static $method;
    public static $module;

    /**
     * Constructeur
     *
     * @param null|string $interface
     * @param null|string $controller
     * @param null|string $method
     * @return \sJo\Core\Loader
     */
    public function __construct($interface = null, $controller = null, $method = null)
    {
        $this->root = dirname(realpath(__DIR__));

        /** Load Settings */
        Lib\Ini::load()
            ->file($this->root . '/settings.default.ini')
            ->toDefine();

        if (defined('SJO_TIMEZONE')) {
            date_default_timezone_set(SJO_TIMEZONE);
        }

        /** Locale */
        $sJo_I18n = new Lib\I18n();
        $sJo_I18n->load('default', $this->root . '/Locale');

        $this->_set('interface', $interface);
        $this->_set('controller', $controller);
        $this->_set('method', $method);

        Helpers\Autoload(SJO_ROOT_APP);
    }

    public function init()
    {
        $this->useModule('ErrorDocument');

        $this->_setModule();

        if (self::$controller) {

            if (class_exists(self::$controllerClass)) {

                $this->instance = new self::$controllerClass ();

                if (get_parent_class($this->instance) == 'sJo\\Core\\Controller\\Controller') {

                    $this->_loadModules();

                    $this->event('viewPreload');

                    new View($this->instance);

                } else {
                    Exception::ErrorDocument('http403', Lib\I18n::__('Controller %s is not extended to %s.', self::$controllerClass, '\\sJo\\Core\\Controller'));
                }
            } else {
                Exception::ErrorDocument('http404', Lib\I18n::__('Controller %s do not exists.', self::$controllerClass));
            }
        } else {
            Exception::ErrorDocument('http404', Lib\I18n::__('CONTROLLER is undefined.'));
        }

        return $this;
    }

    public function display()
    {
        $this->event('viewLoaded');

        if (self::$method) {
            switch (Lib\Env::get('content_type')) {
                case 'json' :
                    header('Content-type:application/json; charset=' . SJO_CHARSET);
                    if (method_exists(self::$controllerClass, self::$method)) {
                        if ($this->instance->Core->Request->hasToken()) {
                            echo json_encode($this->instance->{self::$method}());
                        } else {
                            Exception::ErrorDocument('http403', Lib\I18n::__('Warning ! Prohibited queries.'));
                        }
                    }
                    exit;
                    break;
                default :
                    header('Content-type:text/html; charset=' . SJO_CHARSET);
                    if (method_exists(self::$controllerClass, self::$method)) {
                        if ($this->instance->Core->Request->hasToken()) {
                            $this->instance->{self::$method}();
                        } else {
                            Exception::ErrorDocument('http403', Lib\I18n::__('Warning ! Prohibited queries.'));
                        }
                    }
                    break;
            }
        }

        $this->event('viewCompleted');

        View::load();
    }

    public function instance()
    {
        return $this->instance;
    }

    public function event($event, $options = false)
    {
        $event = '__' . $event;

        if (method_exists($this->instance, $event)) {
            $this->instance->{$event}($options);
        }
    }

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
