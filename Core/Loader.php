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
    private $instance;
    public static $controller;
    public static $method;
    public static $module;

    /**
     * Constructeur
     *
     * @param bool $controller
     * @param bool $method
     * @param bool $module
     * @return \PHPTools\Loader
     */
    public function __construct($controller = false, $method = false, $module = false)
    {
        self::$controller = $controller;
        if (!self::$controller) {
            self::$controller = Libraries\Env::request(PHPTOOLS_CONTROLLER_NAME, PHPTOOLS_CONTROLLER_DEFAULT);
            self::$controller = trim(self::$controller, '/');
            self::$controller = str_replace('/', '\\', self::$controller);
        }

        self::$method = $method;
        if (!$method) {
            self::$method = Libraries\Env::request(PHPTOOLS_METHOD_NAME, PHPTOOLS_METHOD_DEFAULT);
        }

        self::$module = $module;
        if (!$module) {
            self::$module = Libraries\Env::request('module', false);
        }

        if (self::$controller) {

            \PHPTools\Helpers\Autoload(PHPTOOLS_ROOT_APP);

            $className = Module::getClassName('Controller', self::$module, self::$controller);

            if (class_exists($className)) {

                $this->instance = new $className ();

                if (get_parent_class($this->instance) == 'PHPTools\\Controller') {

                    $className = Module::getClassName('Model', self::$module, self::$controller);

                    $this->_load($className, 'Model');

                    if (get_parent_class($this->instance->Model) == 'PHPTools\\Model') {

                        $this->_load('\\PHPTools\\Session', array('Core', 'Session'));
                        $this->_load('\\PHPTools\\Request', array('Core', 'Request'));
                        $this->_load('\\PHPTools\\Alert', array('Core', 'Alert'));
                        $this->_load('\\PHPTools\\Logger', 'Logger');

                        $this->event('viewPreload');

                        new View($this->instance);

                    } else {
                        Exception::ErrorDocument(Libraries\I18n::__('Model %s is not extended to %s.', $className, '\\PHPTools\\Model'))->http403();
                    }
                } else {
                    Exception::ErrorDocument(Libraries\I18n::__('Controller %s is not extended to %s.', $className, '\\PHPTools\\Controller'))->http403();
                }
            } else {
                Exception::ErrorDocument(Libraries\I18n::__('Controller %s do not exists.', $className))->http404();
            }
        } else {
            Exception::ErrorDocument(Libraries\I18n::__('CONTROLLER is undefined.'))->http404();
        }
    }

    public function display()
    {
        $className = '\\Controller\\' . self::$controller;

        $this->event('viewLoaded');

        if (self::$method) {
            switch (Libraries\Env::get('content_type')) {
                case 'json' :
                    header('Content-type:application/json; charset=' . PHPTOOLS_CHARSET);
                    if (method_exists($className, self::$method)) {
                        if ($this->instance->Core->Request->hasToken()) {
                            echo json_encode($this->instance->{self::$method}());
                        } else {
                            Exception::ErrorDocument(Libraries\I18n::__('Warning ! Prohibited queries.'))->http403();
                        }
                    }
                    exit;
                    break;
                default :
                    header('Content-type:text/html; charset=' . PHPTOOLS_CHARSET);
                    if (method_exists($className, self::$method)) {
                        if ($this->instance->Core->Request->hasToken()) {
                            $this->instance->{self::$method}();
                        } else {
                            Exception::ErrorDocument(Libraries\I18n::__('Warning ! Prohibited queries.'))->http403();
                        }
                    }
                    break;
            }
        }

        $this->event('viewCompleted');

        View::inc(self::$controller);
    }

    public function restrictedAccess()
    {
        $this->instance->Core->Session->check();
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
}
