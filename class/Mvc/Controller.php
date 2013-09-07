<?php

namespace PHPTools\Mvc;

class Controller
{
    public function __construct ($controller = 'controller', $method = 'method')
    {
        new \PHPTools\Request();
        new \PHPTools\Alert();

        if (CONTROLLER) {
            $className = 'Controller\\' . CONTROLLER;
            if (class_exists($className)) {

                $class = new $className ();

                if (method_exists($class, '__load')) {
                    $class->__load();
                }

                View::setObject(CONTROLLER, $class);

                switch(\PHPTools\Env::get('content_type')) {
                    case 'json' :
                        header('Content-type:application/json; charset=' . PHPTOOLS_CHARSET);
                        if (method_exists($className, METHOD)) {
                            if(\PHPTools\Request::hasToken()) {
                                echo json_encode(call_user_func($className . '::' . METHOD));
                            } else {
                                header('Content-type:text/html; charset=' . PHPTOOLS_CHARSET);
                                echo 'Warning ! Prohibited queries.';
                            }
                        }
                        exit ;
                        break;
                    default :
                        header('Content-type:text/html; charset=' . PHPTOOLS_CHARSET);
                        if (method_exists($className, METHOD)) {
                            if(\PHPTools\Request::hasToken()) {
                                call_user_func($className . '::' . METHOD);
                            } else {
                                echo 'Warning ! Prohibited queries.';
                                exit;
                            }
                        }
                        break;
                }
            }

            View::inc(CONTROLLER);
        }
    }
}
