<?php

namespace PHPTools\Modules;

use \PHPTools\Libraries as Lib;
use \PHPTools\Libraries\I18n as I18n;

trait Auth
{
    public function signin ()
    {
        if($this->getLogin()) {
            if($this->getPassword()) {
                if($token = $this->canSignin()) {
                    $this->Logger->info('Signin {user}', array(
                        'user' => $this->getLogin()
                    ));
                    $this->Core->Session->signin($token);
                } else {
                    $this->Core->Alert->add(I18n::__('Les informations de connexion sont incorrects'));
                }
            } else {
                $this->Core->Alert->add(I18n::__('Veuillez renseigner votre mot de passe'));
            }
        } else {
            $this->Core->Alert->add(I18n::__('Veuillez renseigner votre identifiant'));
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
