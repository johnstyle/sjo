<?php

namespace sJo\Module\Admin;

use sJo\Controller\Component\Component;
use sJo\Module\Dependencies;

class Loader
{
    public function __construct(Component $component)
    {
        Dependencies::check(array(
            'sJo\Db\PDO\Drivers\Mysql'
        ));
    }

    public function __init()
    {
        return new Model\Admin();
    }
}
