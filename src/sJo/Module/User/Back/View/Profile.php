<?php

use sJo\Loader\Router;
use sJo\Module\User\Model\User;
use sJo\View\Helper;
use sJo\Libraries as Lib;

$this->header();

Helper\Panel::create(array(
    'col' => 6,
    'title' => 'Edition',
    'class' => 'panel-primary',
    'elements' => Helper\Fieldset::create(array(
        Helper\Token::create(Router::getToken('update')),
        Helper\Input::create(array(
            'type' => 'email',
            'name' => 'email',
            'label' => Lib\I18n::__('Email address'),
            'placeholder' => Lib\I18n::__('Enter email'),
            'value' => User::logged()->email
        )),
        Helper\Input::create(array(
            'type' => 'text',
            'name' => 'name',
            'label' => Lib\I18n::__('Name'),
            'placeholder' => Lib\I18n::__('Enter name'),
            'value' => User::logged()->name
        ))
    )),
    'footer' => Helper\Button::create(array(
        'class' => 'pull-right btn-primary',
        'value' => Lib\I18n::__('Save')
    ))
))->display();

$this->footer();
