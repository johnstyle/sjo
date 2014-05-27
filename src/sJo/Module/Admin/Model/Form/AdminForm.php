<?php

namespace sJo\Module\Admin\Model\Form;

use sJo\Libraries\I18n;
use sJo\Model\Component\Form;
use sJo\Module\Admin\Model\Admin;

/**
 * @property $id int
 * @property $user_id $id
 * @property string string
 */
class AdminForm extends Admin
{
    use Form;

    /**
     * Constructor
     */
    public function __construct($id = null)
    {
        $this->__form = array(
            'fields' => array(
                'email' => array(
                    'type' => 'email',
                    'label' => I18n::__('Email address')
                ),
                'name' => array(
                    'type' => 'text',
                    'label' => I18n::__('Name')
                ),
                'password' => array(
                    'type' => 'password',
                    'label' => I18n::__('Password')
                ),
                'password2' => array(
                    'type' => 'password',
                    'label' => I18n::__('Retype password'),
                    'validate' => ':password'
                )
            )
        );

        parent::__construct($id);
    }
}
