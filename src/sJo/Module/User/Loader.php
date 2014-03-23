<?php

namespace sJo\Module\User;

use sJo\Controller\Component\Component;
use sJo\Module\Dependencies;
use sJo\Module\User\Back\Controller\Auth;

class Loader
{
    public function __construct(Component $component)
    {
        Dependencies::check(array(
            'sJo\Db\PDO\Drivers\Mysql'
        ));

        Auth::secure();
    }

    public function __init()
    {
        return new Model\User();
    }
}
