<?php
/**
 * PHPTools
 *
 * PHP version 5
 *
 * @package  PHPTools
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/PHPTools.git
 */

namespace PHPTools;

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

        try {
            if (CONTROLLER) {

                \PHPTools\Helpers\Autoload(PHPTOOLS_ROOT_APP);

                try {
                    $className = '\\Controller\\' . CONTROLLER;

                    if (class_exists($className)) {
                        $this->instance = new $className ();
                        $this->_initModel();
                    } else {
                        throw new Exception('Controller <b>\\Controller\\' . CONTROLLER . '</b> do not extsist.');
                    }                
                } catch(Exception $Exception) {
                    $Exception->showError();
                }
            } else {
                throw new Exception('CONTROLLER is undefined.');
            }
        } catch(Exception $Exception) {
            $Exception->showError();
        }
    }

    public function event ($event, $options = false)
    {
        $event = '__' . $event;

        if (method_exists($this->instance, $event)) {
            $this->instance->{$event}($options);
        }
    }

    public function display ()
    {
        $className = '\\Controller\\' . CONTROLLER;
        
        $this->_initCore('Session');
        $this->_initCore('Request');
        $this->_initCore('Alert');

        $this->_initView();

        $this->event('restrictedAccess');
        $this->event('load');

        if(METHOD) {
            switch(Libraries\Env::get('content_type')) {
                case 'json' :
                    header('Content-type:application/json; charset=' . PHPTOOLS_CHARSET);
                        if (method_exists($className, METHOD)) {
                            try {
                                if($this->instance->Core->Request->hasToken()) {
                                    echo json_encode($this->instance->{METHOD}());
                                } else {
                                    throw new Exception('Warning ! Prohibited queries.');
                                }
                            } catch(Exception $Exception) {
                                $Exception->showError();
                            }
                        }
                    exit ;
                    break;
                default :
                    header('Content-type:text/html; charset=' . PHPTOOLS_CHARSET);
                    if (method_exists($className, METHOD)) {
                        try {
                            if($this->instance->Core->Request->hasToken()) {
                                $this->instance->{METHOD}();
                            } else {
                                throw new Exception('Warning ! Prohibited queries.');
                            }
                        } catch(Exception $Exception) {
                            $Exception->showError();
                        }    
                    }
                    break;
            }
        }

        View::inc(CONTROLLER);
    }

    private function _initModel ()
    {
        $className = '\\Model\\' . CONTROLLER;

        if (class_exists($className)) {
            $this->instance->Model = new $className ();
        }
    }

    private function _initCore ($class)
    {
        $className = '\\PHPTools\\' . $class;

        if (class_exists($className)) {
            $this->instance->Core->{$class} = new $className ();
        }
    }

    private function _initView ()
    {
        $View = new View($this->instance);
    }    
}
