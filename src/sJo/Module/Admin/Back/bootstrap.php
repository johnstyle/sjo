<?php

use sJo\Loader\Router;
use sJo\View\Helper;
use sJo\Libraries\I18n;
use sJo\Module\Admin\Back\Controller\Auth;

Auth::secure();

Helper\Menu::addRegistry('top', array(
    'title' => I18n::__('My profil'),
    'link' => Router::linkBack('Admin/Profile')
));

Helper\Menu::addRegistry('sidebar', array(
    'title' => I18n::__('Admins'),
    'children' => array(
        array(
            'title' => I18n::__('Manage admins'),
            'link' => Router::linkBack('Admin/Manager')
        )
    )
));
