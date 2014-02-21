<?php

namespace sJo\Module\User\Back\Controller;

use sJo\Controller\Controller;
use sJo\Libraries as Lib;
use sJo\Module\User\Model\User;

class Auth extends Controller
{
    public function signin()
    {
        if(Lib\Env::post('email')) {
            if(Lib\Env::post('password')) {
                if($token = User::getInstance()->exists(Lib\Env::post('email'), Lib\Env::post('password'))) {
                    $this->Logger->info('Signin {user}', array(
                        'user' => Lib\Env::post('email')
                    ));
                    $this->Core->Session->signin($token);
                } else {
                    $this->Core->Alert->set(Lib\I18n::__('Les informations de connexion sont incorrects'));
                }
            } else {
                $this->Core->Alert->set(Lib\I18n::__('Veuillez renseigner votre mot de passe'));
            }
        } else {
            $this->Core->Alert->set(Lib\I18n::__('Veuillez renseigner votre identifiant'));
        }
    }

    public function signout ()
    {
        $this->Logger->info('Signout {user}', array(
            'user' => Lib\Env::session('token')
        ));
        $this->Core->Session->signout();
    }
}
