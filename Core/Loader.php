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
    
    public function __construct ()
    {
        defined('CONTROLLER') OR define('CONTROLLER', Libraries\Env::request(PHPTOOLS_CONTROLLER_NAME, PHPTOOLS_CONTROLLER_DEFAULT));
        defined('METHOD') OR define('METHOD', Libraries\Env::request(PHPTOOLS_METHOD_NAME, PHPTOOLS_METHOD_DEFAULT));

        if (CONTROLLER) {

            \PHPTools\Helpers\Autoload(PHPTOOLS_ROOT_APP);

            try {
                if($this->initController()) {
                    View::inc(CONTROLLER);
                } else {
                    throw new Exception('Controller <b>\\Controller\\' . CONTROLLER . '</b> do not extsist.');
                }
            } catch(Exception $Exception) {
                $Exception->showError();
            }
        }
    }

    private function initController ()
    {
        $className = '\\Controller\\' . CONTROLLER;
        
        if (class_exists($className)) {

            $this->instance = new $className ();

            $this->initCore('Session');
            $this->initCore('Request');
            $this->initCore('Alert');

            $this->initModel();
            $this->initView();

            if (method_exists($this->instance, '__restrictedAccess')) {
                $this->instance->__restrictedAccess();
            }

            if (method_exists($this->instance, '__load')) {
                $this->instance->__load();
            }

            switch(Libraries\Env::get('content_type')) {
                case 'json' :
                    header('Content-type:application/json; charset=' . PHPTOOLS_CHARSET);
                    if (method_exists($className, METHOD)) {
                        if($this->instance->Core->Request->hasToken()) {
                            echo json_encode($this->instance->{METHOD}());
                        } else {
                            http_response_code(401);
                            header('Content-type:text/html; charset=' . PHPTOOLS_CHARSET);
                            echo 'Warning ! Prohibited queries.';
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
                            http_response_code(401);
                            echo 'Warning ! Prohibited queries.';
                            exit;
                        }
                    }
                    break;
            }
            return true;
        }
        return false;
    }

    private function initModel ()
    {
        $className = 'Model\\' . CONTROLLER;

        if (class_exists($className)) {
            $this->instance->Model = new $className ();
        }
    }

    private function initView ()
    {
        $View = new View($this->instance);
    }

    private function initCore ($class)
    {
        $className = '\\PHPTools\\' . $class;
        $this->instance->Core->{$class} = new $className ();
    }     
}
