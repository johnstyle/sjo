<?php

use sJo\Loader\Router;
use sJo\View\Helper;
use sJo\Libraries\I18n;
use sJo\Module\Admin\Back\Controller\Auth;

Auth::secure();

Helper\Menu::addRegistry('top', array(
    Helper\Link::create(array(
        'elements' => I18n::__('My profil'),
        'attributes' => array(
            'href' => Router::linkBack('Admin/Profile')
        )
    ))
));

Helper\Menu::addRegistry('sidebar', array(
    Helper\Link::create(array(
        'elements' => I18n::__('Manage admins'),
        'attributes' => array(
            'href' => Router::linkBack('Admin/Manager')
        )
    ))
));
