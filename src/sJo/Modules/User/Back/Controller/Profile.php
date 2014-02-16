<?php

namespace sJo\Modules\User\Back\Controller;

use sJo\Controller\Controller;
use sJo\Libraries\I18n;
use sJo\Modules\User\Model\User;
use sJo\View\Helper;

class Profile extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->name = I18n::__('My profil');

        Helper\Menu::main(array(
            'title' => I18n::__('My profil'),
            'controller' => 'User\Profile'
        ));
    }

    public function update ()
    {
        parent::update(new User());
    }
}
