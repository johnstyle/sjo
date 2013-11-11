<?php

/**
 * Loader
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
 * Loader
 *
 * @package  PHPTools
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/PHPTools.git
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
     * @return \PHPTools\Loader
     */
    public function __construct($controller = false, $method = false)
    {
        $this->_set('controller', $controller);
        $this->_set('method', $method);

        \PHPTools\Helpers\Autoload(PHPTOOLS_ROOT_APP);
    }

    public function init()
    {
        $this->useModule('ErrorDocument');

        $this->_setModule();

        if (self::$controller) {

            if (class_exists(self::$controllerClass)) {

                $this->instance = new self::$controllerClass ();

                if (get_parent_class($this->instance) == 'PHPTools\\Controller') {

                    $this->_load(self::$modelClass, 'Model');

                    if (get_parent_class($this->instance->Model) == 'PHPTools\\Model') {

                        $this->_load('\\PHPTools\\Session', array('Core', 'Session'));
                        $this->_load('\\PHPTools\\Request', array('Core', 'Request'));
                        $this->_load('\\PHPTools\\Alert', array('Core', 'Alert'));
                        $this->_load('\\PHPTools\\Logger', 'Logger');

                        $this->_loadModules();

                        $this->event('viewPreload');

                        new View($this->instance);

                    } else {
                        Exception::ErrorDocument('http403', Libraries\I18n::__('Model %s is not extended to %s.', self::$modelClass, '\\PHPTools\\Model'));
                    }
                } else {
                    Exception::ErrorDocument('http403', Libraries\I18n::__('Controller %s is not extended to %s.', self::$controllerClass, '\\PHPTools\\Controller'));
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
                    header('Content-type:application/json; charset=' . PHPTOOLS_CHARSET);
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
                    header('Content-type:text/html; charset=' . PHPTOOLS_CHARSET);
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

            $hookName = '\\Hooks\\' . str_replace('\\PHPTools\\', '', $className);

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
                    self::$controller = Libraries\Env::request(PHPTOOLS_CONTROLLER_NAME, PHPTOOLS_CONTROLLER_DEFAULT);
                    self::$controller = trim(self::$controller, '/');
                    self::$controller = str_replace('/', '\\', self::$controller);
                }
                self::$controllerClass = '\\Controller\\' . Loader::$controller;
                self::$modelClass = '\\Model\\' . Loader::$controller;
                self::$viewFile = PHPTOOLS_ROOT_VIEW . '/' . str_replace('\\', '/', self::$controller) . '.php';
                break;
            case 'method':
                self::$method = $value;
                if (!self::$method) {
                    self::$method = Libraries\Env::request(PHPTOOLS_METHOD_NAME, PHPTOOLS_METHOD_DEFAULT);
                }
                break;
        }
    }
}
