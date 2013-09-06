<?php

namespace PHPTools\Mvc;

class Controler
{
    public function __construct ($controler = 'controler', $method = 'method')
    {
        defined('CONTROLER') OR define('CONTROLER', trim(\PHPTools\Env::get($controler, 'Home'), '/'));
        defined('METHOD') OR define('METHOD', \PHPTools\Env::get($method));

        if (CONTROLER) {
            $className = 'Controler\\' . str_replace('/', '\\', CONTROLER);
            if (class_exists($className)) {

                $class = new $className ();

                if (method_exists($class, '__load')) {
                    $class->__load();
                }

                View::setObject(CONTROLER, $class);

                switch(\PHPTools\Env::get('content_type')) {
                    case 'json' :
                        header('Content-type:application/json; charset=' . PHPTOOLS_CHARSET);
                        if (method_exists($className, 'json' . METHOD)) {
                            call_user_func($className . '::json' . METHOD);
                        }
                        exit ;
                        break;
                    default :
                        header('Content-type:text/html; charset=' . PHPTOOLS_CHARSET);
                        if (method_exists($className, METHOD)) {
                            call_user_func($className . '::' . METHOD);
                        }                        
                        break;
                }

                View::inc(CONTROLER);
            }
        }
    }
}
