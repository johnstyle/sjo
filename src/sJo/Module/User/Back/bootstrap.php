<?php

use sJo\Loader\Router;
use sJo\View\Helper;
use sJo\Libraries\I18n;

Helper\Menu::addRegistry('top', array(
    'title' => I18n::__('My profil'),
    'link' => Router::linkBack('User/Profile')
));

Helper\Menu::addRegistry('sidebar', array(
    'title' => I18n::__('Users'),
    'children' => array(
        array(
            'title' => I18n::__('Manage Users'),
            'link' => Router::linkBack('User/Manager')
        )
    )
));
