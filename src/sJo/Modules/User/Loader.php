<?php

namespace sJo\Modules\User;

use sJo\Core\Dependencies;
use sJo\View\Helper;
use sJo\Libraries\I18n;

class Loader
{
    public function __construct($instance)
    {
        Dependencies::check(array(
            'sJo\Db\PDO\Drivers\Mysql'
        ));

        Helper\Menu::sidebar()->addItem(array(
            'title' => I18n::__('My profil'),
            'controller' => 'User\Profile'
        ));

        Helper\Menu::top()->addItem(array(
            'title' => I18n::__('My profil'),
            'controller' => 'User\Profile'
        ));

        $instance->Core->Session->check('User\Auth');
    }

    public function init()
    {
        return new Model\User();
    }
}
