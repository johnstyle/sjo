<?php

use sJo\Module\User\Model\User;
use sJo\Loader\Router;
use sJo\View\Helper;
use sJo\Libraries as Lib;

Helper\Panel::create(array(
    'col' => 6,
    'title' => User::getInstance()->getPrimaryValue() ? Lib\I18n::__('Edit user') : Lib\I18n::__('Create user'),
    'class' => User::getInstance()->getPrimaryValue() ? 'panel-primary' : 'panel-success',
    'elements' => Helper\Fieldset::create(array(
        Helper\Token::create(Router::getToken()),
        Helper\Input::create(array(
            'type' => 'email',
            'name' => 'email',
            'label' => Lib\I18n::__('Email address'),
            'placeholder' => Lib\I18n::__('Enter email'),
            'value' => User::getInstance()->request('email')
        )),
        Helper\Input::create(array(
            'type' => 'text',
            'name' => 'name',
            'label' => Lib\I18n::__('Name'),
            'placeholder' => Lib\I18n::__('Enter name'),
            'value' => User::getInstance()->request('name')
        ))
    )),
    'footer' => Helper\ButtonGroup::create(array(
        'class' => 'pull-right',
        'elements' => array(
            Helper\Button::create(array(
                'name' => 'saveAndStay',
                'value' => Lib\I18n::__('Save and stay')
            )),
            Helper\Button::create(array(
                'name' => 'saveAndCreate',
                'value' => Lib\I18n::__('Save and create new'),
                'class' => 'btn-warning'
            )),
            Helper\Button::create(array(
                'value' => Lib\I18n::__('Save and back to list'),
                'class' => 'btn-primary'
            ))
        )
    ))
))->display();
