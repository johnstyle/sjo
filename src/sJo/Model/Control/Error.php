<?php

namespace sJo\Model\Control;

use sJo\Loader\Alert;

trait Error
{
    private $__errors = array();

    private function setError ($name, $message)
    {
        array_push($this->__errors, $name);

        Alert::set($message);
    }

    private function hasErrors ()
    {
        return (bool) (count($this->__errors)
            && !empty($this->__errors));
    }

    protected function inError ($name)
    {
        return in_array($name, $this->__errors);
    }
}
