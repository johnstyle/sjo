<?php

namespace PHPTools\Modules\User;

class Loader
{
    public function __construct($instance)
    {
        $instance->Core->Session->check('User\\Auth');
    }

    public function init()
    {
        return new Core\User();
    }
}
