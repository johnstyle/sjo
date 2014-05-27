<?php

namespace sJo\Module\Admin;

use sJo\Module\Dependencies;

class Loader
{
    public function __construct()
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
