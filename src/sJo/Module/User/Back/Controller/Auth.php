<?php

namespace sJo\Module\User\Back\Controller;

use sJo\Controller\Controller;
use sJo\Libraries as Lib;
use sJo\Loader\Alert;
use sJo\Module\User\Model\User;

class Auth extends Controller
{
    public function signin()
    {
        if(Lib\Env::post('email')) {
            if(Lib\Env::post('password')) {
                if($token = User::getInstance()->exists(Lib\Env::post('email'), Lib\Env::post('password'))) {
                    $this->component->logger->info('Signin {user}', array(
                        'user' => Lib\Env::post('email')
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
            'user' => Lib\Env::session('token')
        ));
        $this->component->session->signout();
    }
}
