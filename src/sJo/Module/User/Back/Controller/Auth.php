<?php

namespace sJo\Module\User\Back\Controller;

use sJo\Controller\Controller;
use sJo\Http\Http;
use sJo\Libraries as Lib;
use sJo\Loader\Alert;
use sJo\Loader\Logger;
use sJo\Loader\Router;
use sJo\Loader\Token;
use sJo\Module\User\Model\User;
use sJo\Request\Request;

class Auth extends Controller
{
    public function signin()
    {
        if(Request::env('POST')->email->exists()) {

            if(Request::env('POST')->password->exists()) {

                if($id = User::getInstance()->exists(Request::env('POST')->email->val(), Request::env('POST')->password->val())) {

                    Logger::getInstance()->info('Signin {user}', array(
                        'user' => Request::env('POST')->email->val()
                    ));

                    Request::env('SESSION')->id = $id;
                    Request::env('SESSION')->token = Token::get($id);

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

    public function signout ()
    {
        Logger::getInstance()->info('Signout {user}', array(
            'user' => Request::env('SESSION')->token->val()
        ));

        Request::env('COOKIES')->destroy();
        Request::env('SESSION')->destroy();

        Http::redirect(SJO_BASEHREF);
    }

    public static function secure ()
    {
        if (Router::$controller !== 'User\\Auth') {
            if (!self::isLoggedUser()) {
                http_response_code(401);
                Http::redirect(Router::linkBack('User/Auth', array('redirect' => Request::env('SERVER')->REQUEST_URI->val())));
            }
        } elseif (self::isLoggedUser() && !Router::$method) {
            Http::redirect(SJO_BASEHREF);
        }
    }

    public static function isLoggedUser()
    {
        if (Request::env('SESSION')->id->exists()
            && Request::env('SESSION')->token->eq(Token::get(Request::env('SESSION')->id->val()))) {
            return true;
        }
        return false;
    }
}
