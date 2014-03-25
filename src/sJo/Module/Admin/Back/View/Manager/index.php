<?php

use sJo\Module\Admin\Model\Admin;
use sJo\View\Helper;
use sJo\Libraries as Lib;

Helper\Panel::create(array(
    'col' => 6,
    'title' => Lib\I18n::__('Liste des pages'),
    'class' => 'panel-primary',
    'elements' => Helper\Table::create(array(
        'tbody' => Admin::getInstance(),
        'actions' => array('edit', 'delete')
    ))
))->display();
