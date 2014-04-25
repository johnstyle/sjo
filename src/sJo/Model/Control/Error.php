<?php

namespace sJo\Model\Control;

use sJo\Loader\Alert;

trait Error
{
    protected $__errors = array();

    protected function setError ($name, $message)
    {
        array_push($this->__errors, $name);

        Alert::set($message);
    }

    protected function hasErrors ()
    {
        return (bool) (count($this->__errors)
            && !empty($this->__errors));
    }

    protected function inError ($name)
    {
        return in_array($name, $this->__errors);
    }
}
