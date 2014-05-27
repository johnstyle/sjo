<?php

namespace sJo\Module\Admin\Back\Controller;

use sJo\Controller\Controller;
use sJo\Libraries\I18n;
use sJo\Module\Admin\Model\Admin;

class Profile extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->name = I18n::__('My profil');
    }

    public function update ()
    {
        parent::update(Admin::logged());
    }
}
