<?php

namespace sJo\Module\User;

use sJo\Controller\Component\Component;
use sJo\Module\Dependencies;
use sJo\View\Helper;
use sJo\Libraries\I18n;

class Loader
{
    public function __construct(Component $component)
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

        $component->session->check('User\Auth');
    }

    public function __init()
    {
        return new Model\User();
    }
}
