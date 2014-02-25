<?php

use sJo\View\Helper;
use sJo\Libraries\I18n;

Helper\Menu::addRegistry('top', array(
    'title' => I18n::__('My profil'),
    'controller' => 'User\Profile'
));
