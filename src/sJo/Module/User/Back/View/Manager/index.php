<?php

use sJo\Module\User\Model\User;
use sJo\View\Helper;
use sJo\Libraries as Lib;

Helper\Panel::create(array(
    'col' => 6,
    'title' => Lib\I18n::__('Liste des pages'),
    'color' => 'primary',
    'elements' => Helper\Table::create(array(
        'tbody' => User::getInstance(),
        'actions' => array('edit', 'delete')
    ))
))->display();
