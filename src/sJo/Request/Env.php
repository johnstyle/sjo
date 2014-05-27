<?php

namespace sJo\Request;

use sJo\Request\Session;
use sJo\Object\Tree;

/**
 * Class Env
 * @package sJo\Request
 */
class Env extends Tree
{
    public function __construct(array &$items = null, $key = null)
    {
         switch ($key) {

            case 'SESSION':
                Session::start();
                $items =& Session::$reference;
                break;

            case 'REQUEST':
                $items = array();
                if(isset($_GET)) {
                    $items = array_merge($items, $_GET);
                }
                if(isset($_POST)) {
                    $items = array_merge($items, $_POST);
                }
                if(!count($items)) {
                    $items = null;
                }
                break;
        }

        if ($key) {

            $_VAR = '_' . strtoupper($key);

            if ($items === null) {

                global ${$_VAR};
                $items =& ${$_VAR};
            }
        }

        parent::__construct($items, $key);
    }

    public function destroy()
    {
        parent::destroy();

        switch ($this->key) {

            case 'SESSION':
                Session::destroy();
                break;
        }
    }
}
