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

namespace sJo;

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

    private $instance;
    public static $controller;
    public static $controllerClass;
    public static $modelClass;
    public static $viewFile;
    public static $method;
    public static $module;

    /**
     * Constructeur
     *
     * @param bool $controller
     * @param bool $method
     * @return \sJo\Loader
     */
    public function __construct($controller = false, $method = false)
    {
        $this->_set('controller', $controller);
        $this->_set('method', $method);

        \sJo\Helpers\Autoload(SJO_ROOT_APP);
    }

    public function init()
    {
        $this->useModule('ErrorDocument');

        $this->_setModule();

        if (self::$controller) {

            if (class_exists(self::$controllerClass)) {

                $this->instance = new self::$controllerClass ();

                if (get_parent_class($this->instance) == 'sJo\\Controller') {

                    $this->_load(self::$modelClass, 'Model');

                    if (get_parent_class($this->instance->Model) == 'sJo\\Model') {

                        $this->_load('\\sJo\\Session', array('Core', 'Session'));
                        $this->_load('\\sJo\\Request', array('Core', 'Request'));
                        $this->_load('\\sJo\\Alert', array('Core', 'Alert'));
                        $this->_load('\\sJo\\Logger', 'Logger');

                        $this->_loadModules();

                        $this->event('viewPreload');

                        new View($this->instance);

                    } else {
                        Exception::ErrorDocument('http403', Libraries\I18n::__('Model %s is not extended to %s.', self::$modelClass, '\\sJo\\Model'));
                    }
                } else {
                    Exception::ErrorDocument('http403', Libraries\I18n::__('Controller %s is not extended to %s.', self::$controllerClass, '\\sJo\\Controller'));
                }
            } else {
                Exception::ErrorDocument('http404', Libraries\I18n::__('Controller %s do not exists.', self::$controllerClass));
            }
        } else {
            Exception::ErrorDocument('http404', Libraries\I18n::__('CONTROLLER is undefined.'));
        }
    }

    public function display()
    {
        $this->event('viewLoaded');

        if (self::$method) {
            switch (Libraries\Env::get('content_type')) {
                case 'json' :
                    header('Content-type:application/json; charset=' . SJO_CHARSET);
                    if (method_exists(self::$controllerClass, self::$method)) {
                        if ($this->instance->Core->Request->hasToken()) {
                            echo json_encode($this->instance->{self::$method}());
                        } else {
                            Exception::ErrorDocument('http403', Libraries\I18n::__('Warning ! Prohibited queries.'));
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
                            Exception::ErrorDocument('http403', Libraries\I18n::__('Warning ! Prohibited queries.'));
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

    private function _load($className, $name)
    {
        if (class_exists($className)) {

            $hookName = '\\Hooks\\' . str_replace('\\sJo\\', '', $className);

            if (class_exists($hookName)) {
                Libraries\Obj::tree($this->instance, $name, new $hookName ($this->instance));
            } else {
                Libraries\Obj::tree($this->instance, $name, new $className ($this->instance));
            }
        }
    }

    private function _set($type, $value = false)
    {
        switch($type) {
            case 'controller':
                self::$controller = $value;
                if (!self::$controller) {
                    self::$controller = Libraries\Env::request(SJO_CONTROLLER_NAME, SJO_CONTROLLER_DEFAULT);
                    self::$controller = trim(self::$controller, '/');
                    self::$controller = str_replace('/', '\\', self::$controller);
                }
                self::$controllerClass = '\\Controller\\' . Loader::$controller;
                self::$modelClass = '\\Model\\' . Loader::$controller;
                self::$viewFile = SJO_ROOT_VIEW . '/' . str_replace('\\', '/', self::$controller) . '.php';
                break;
            case 'method':
                self::$method = $value;
                if (!self::$method) {
                    self::$method = Libraries\Env::request(SJO_METHOD_NAME, SJO_METHOD_DEFAULT);
                }
                break;
        }
    }
}
