<?php

namespace sJo\Module\Admin\Back\Controller;

use sJo\Controller\Controller;
use sJo\Http\Http;
use sJo\Libraries\I18n;
use sJo\Loader\Alert;
use sJo\Loader\Router;
use sJo\Request\Request;
use sJo\View\Helper;
use sJo\Module\Admin\Model\Admin;

class Manager extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->name = I18n::__('List of admins');
    }

    public function edit ()
    {
        Helper\Menu::addRegistry('main', array(
            Helper\Link::create(array(
                'elements' => I18n::__('Cancel'),
                'attributes' => array(
                    'class' => 'bg-danger remove',
                    'href' => Router::linkBack('Admin/Manager')
                )
            ))
        ));

        parent::edit(new Admin());
    }

    public function delete ()
    {
        if (Request::env('REQUEST')->{Admin::getInstance()->getPrimaryKey()}->val() == 1) {
            Alert::set(I18n::__('Vous ne pouvez pas supprimer cet administrateur.'));
            Http::redirect(Router::linkBack(Router::$controller));
        }

        parent::delete(new Admin());
    }

    public function index ()
    {
        Helper\Menu::addRegistry('main', array(
            Helper\Link::create(array(
                'elements' => I18n::__('Create admin'),
                'attributes' => array(
                    'class' => 'bg-success plus',
                    'href' => Router::linkBack('Admin/Manager::edit')
                )
            ))
        ));
    }
}
