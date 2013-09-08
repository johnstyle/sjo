<?php

namespace PHPTools;

class Exception extends \Exception
{
    public function __construct($msg = NULL, $code = 0) 
    {
        parent::__construct($msg, $code);
    }

    public function showError()
    {
        die('<div style="color:red">' . $this->getMessage() . '</div>');
    }
}
