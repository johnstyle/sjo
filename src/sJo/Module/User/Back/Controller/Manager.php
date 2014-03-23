<?php

namespace sJo\Module\User\Back\Controller;

use sJo\Controller\Controller;
use sJo\Http\Http;
use sJo\Libraries\I18n;
use sJo\Loader\Alert;
use sJo\Loader\Router;
use sJo\Request\Request;
use sJo\View\Helper;
use sJo\Module\User\Model\User;

class Manager extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->name = I18n::__('List of users');
    }

    public function edit ()
    {
        Helper\Menu::addRegistry('main', array(
            'title' => I18n::__('Cancel'),
            'controller' => 'User/Manager',
            'icon' => 'remove',
            'class' => 'bg-danger'
        ));

        parent::edit(new User());
    }

    public function delete ()
    {
        if (Request::env('REQUEST')->{User::getInstance()->getPrimaryKey()}->val() == 1) {
            Alert::set(I18n::__('Vous ne pouvez pas supprimer cet utilisateur.'));
            Http::redirect(Router::link(Router::$controller));
        }

        parent::delete(new User());
    }

    public function index ()
    {
        Helper\Menu::addRegistry('main', array(
            'title' => I18n::__('Create user'),
            'controller' => 'User/Manager::edit',
            'icon' => 'plus',
            'class' => 'bg-success'
        ));
    }
}
