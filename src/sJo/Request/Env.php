<?php

namespace sJo\Request;

use sJo\Object\Tree;

/**
 * Class Env
 * @package sJo\Request
 */
class Env
{
    use Tree;

    public function __construct($var = null)
    {
        if ($var) {

            $var = '_' . strtoupper($var);

            global $$var;

            $this->var = $var;
            $this->data =& $$var;
        }
    }
}
