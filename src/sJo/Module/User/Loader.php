<?php

namespace sJo\Module\User;

use sJo\Controller\Controller;
use sJo\Core\Dependencies;
use sJo\View\Helper;
use sJo\Libraries\I18n;

class Loader
{
    public function __construct(Controller $controller)
    {
        Dependencies::check(array(
            'sJo\Db\PDO\Drivers\Mysql'
        ));

        Helper\Menu::addRegistry('sidebar', array(
            'title' => I18n::__('My profil'),
            'controller' => 'User\Profile'
        ));

        Helper\Menu::addRegistry('top', array(
            'title' => I18n::__('My profil'),
            'controller' => 'User\Profile'
        ));

        $controller->Core->Session->check('User\Auth');
    }

    public function __init()
    {
        return new Model\User();
    }
}
