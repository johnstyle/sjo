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

    public function __construct ($controller = false, $method = false)
    {
        if(!$controller) {
            $controller = Libraries\Env::request(PHPTOOLS_CONTROLLER_NAME, PHPTOOLS_CONTROLLER_DEFAULT);
            $controller = trim($controller, '/');
            $controller = str_replace('/', '\\', $controller);
        }

        if(!$method) {
            $method = Libraries\Env::request(PHPTOOLS_METHOD_NAME, PHPTOOLS_METHOD_DEFAULT);
        }

        define('CONTROLLER', $controller);
        define('METHOD', $method);

        if (CONTROLLER) {

            \PHPTools\Helpers\Autoload(PHPTOOLS_ROOT_APP);

            $className = '\\Controller\\' . CONTROLLER;

            if (class_exists($className)) {
                $this->instance = new $className ();

                if(get_parent_class($this->instance) == 'PHPTools\Controller') {

                    $className = '\\Model\\' . CONTROLLER;

                    if (class_exists($className)) {
                        $this->instance->Model = new $className ();
                    }
                } else {
                    Exception::error(Libraries\I18n::__('Controller %s is not extended to %s.', '<b>' . $className . '</b>', '<b>\\PHPTools\\Controller</b>'));
                }                      
            } else {
                Exception::error(Libraries\I18n::__('Controller %s do not exists.', '<b>' . $className . '</b>'));
            }
        } else {
            Exception::error(Libraries\I18n::__('CONTROLLER is undefined.'));
        }

        $this->_initCore('Session');
        $this->_initCore('Request');
        $this->_initCore('Alert');

        $this->event('viewPreload');

        new View($this->instance);
    }

    public function display ()
    {
        $className = '\\Controller\\' . CONTROLLER;

        $this->event('viewLoaded');

        if(METHOD) {
            switch(Libraries\Env::get('content_type')) {
                case 'json' :
                    header('Content-type:application/json; charset=' . PHPTOOLS_CHARSET);
                        if (method_exists($className, METHOD)) {
                            if($this->instance->Core->Request->hasToken()) {
                                echo json_encode($this->instance->{METHOD}());
                            } else {
                                Exception::error(Libraries\I18n::__('Warning ! Prohibited queries.'));
                            }
                        }
                    exit ;
                    break;
                default :
                    header('Content-type:text/html; charset=' . PHPTOOLS_CHARSET);
                    if (method_exists($className, METHOD)) {
                        if($this->instance->Core->Request->hasToken()) {
                            $this->instance->{METHOD}();
                        } else {
                            Exception::error(Libraries\I18n::__('Warning ! Prohibited queries.'));
                        }
                    }
                    break;
            }
        }

        $this->event('viewCompleted');

        View::inc(CONTROLLER);
    }

    public function restrictedAccess ()
    {
        $this->instance->Core->Session->check();
    }

    public function event ($event, $options = false)
    {
        $event = '__' . $event;

        if (method_exists($this->instance, $event)) {
            $this->instance->{$event}($options);
        }
    }

    private function _initCore ($class)
    {
        $className = '\\PHPTools\\' . $class;

        if (class_exists($className)) {
            $hookName = '\\Hooks\\' . $class;
            if (class_exists($hookName)) {
                $this->instance->Core->{$class} = new $hookName ();
            } else {
                $this->instance->Core->{$class} = new $className ();
            }
        }
    }
}
