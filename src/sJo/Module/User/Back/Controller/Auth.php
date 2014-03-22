<?php

namespace sJo\Module\User\Back\Controller;

use sJo\Controller\Controller;
use sJo\Libraries as Lib;
use sJo\Loader\Alert;
use sJo\Module\User\Model\User;
use sJo\Request\Request;

class Auth extends Controller
{
    public function signin()
    {
        if(Request::env('POST')->email->exists()) {
            if(Request::env('POST')->password->exists()) {
                if($token = User::getInstance()->exists(Request::env('POST')->email->val(), Request::env('POST')->password->val())) {
                    $this->component->logger->info('Signin {user}', array(
                        'user' => Request::env('POST')->email->val()
                    ));
                    $this->component->session->signin($token);
                } else {
                    Alert::set(Lib\I18n::__('Les informations de connexion sont incorrects'));
                }
            } else {
                Alert::set(Lib\I18n::__('Veuillez renseigner votre mot de passe'));
            }
        } else {
            Alert::set(Lib\I18n::__('Veuillez renseigner votre identifiant'));
        }
    }

    public function signout ()
    {
        $this->component->logger->info('Signout {user}', array(
            'user' => Request::env('SESSION')->token->val()
        ));
        $this->component->session->signout();
    }
}
