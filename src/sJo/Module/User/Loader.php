<?php

namespace sJo\Module\User;

use sJo\Controller\Component\Component;
use sJo\Module\Dependencies;

class Loader
{
    public function __construct(Component $component)
    {
        Dependencies::check(array(
            'sJo\Db\PDO\Drivers\Mysql'
        ));

        $component->session->check('User\Auth');
    }

    public function __init()
    {
        return new Model\User();
    }
}
