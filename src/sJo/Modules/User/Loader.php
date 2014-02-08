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

        Helper\Menu::addItem('sidebar', array(
            'title' => I18n::__('My profil'),
            'controller' => 'User\Profile'
        ));

        Helper\Menu::addItem('top', array(
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
