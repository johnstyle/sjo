<?php

namespace sJo\Module\Admin\Back\Controller;

use sJo\Controller\Controller;
use sJo\Http\Http;
use sJo\Libraries as Lib;
use sJo\Loader\Alert;
use sJo\Loader\Logger;
use sJo\Loader\Token;
use sJo\Module\Admin\Model\Admin;
use sJo\Request\Request;

class Auth extends Controller
{
    use \sJo\Controller\Auth;

    protected static $authModel = 'Admin';

    public function signin()
    {
        if(Request::env('POST')->email->exists()) {

            if(Request::env('POST')->password->exists()) {

                if($id = Admin::getInstance()->exists(Request::env('POST')->email->val(), Request::env('POST')->password->val())) {

                    Logger::getInstance()->info('Signin {admin}', array(
                        'admin' => Request::env('POST')->email->val()
                    ));

                    self::session()->id = $id;
                    self::session()->token = Token::get($id);

                    $url = SJO_BASEHREF;
                    if (preg_match('#^(\./|/)#', Request::env('GET')->redirect->val())) {
                        $url = Request::env('GET')->redirect->val();
                    }
                    Http::redirect($url);
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
}
