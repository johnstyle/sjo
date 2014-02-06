<?php

namespace sJo\Modules\User;

use sJo\Core\Dependencies;

class Loader
{
    public function __construct($instance)
    {
        Dependencies::check(array(
            'sJo\Db\PDO\Drivers\Mysql'
        ));

        $instance->Core->Session->check('User\\Auth');
    }

    public function init()
    {
        return new Model\User();
    }
}
