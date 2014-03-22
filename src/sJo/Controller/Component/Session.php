<?php

/**
 * Gestion des sesions de connexion
 *
 * PHP version 5
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */

namespace sJo\Controller\Component;

use sJo\Loader\Token;
use sJo\Libraries as Lib;
use sJo\Http\Http;
use sJo\Loader\Router;
use sJo\Request\Request;

/**
 * Gestion des sesions de connexion
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */
class Session
{
    public function check($auth)
    {
        \sJo\Request\Session::start(false);

        if (Router::$controller !== $auth) {
            if (!$this->isActive()) {
                http_response_code(401);
                $this->redirect(Router::link($auth, array('redirect' => Request::env('SERVER')->REQUEST_URI->val())));
            }
        } elseif ($this->isActive() && !Router::$method) {
            $this->redirect();
        }
    }

    public function signin($id, $url = SJO_BASEHREF)
    {
        Request::env('SESSION')->id = $id;
        Request::env('SESSION')->token = Token::get($id);
        $this->redirect($url);
    }

    public function signout($url = SJO_BASEHREF)
    {
        if (Request::env('COOKIES')->exists()) {
            foreach (Request::env('COOKIES')->getArray() as $name => $value) {
                unset(Request::env('COOKIES')->{$name});
            }
        }

        \sJo\Request\Session::destroy();

        $this->redirect($url);
    }

    public function redirect($url = SJO_BASEHREF)
    {
        if (preg_match("#^(\./|/)#", Request::env('GET')->redirect->val())) {
            Http::redirect(Request::env('GET')->redirect->val());
        } else {
            Http::redirect($url);
        }
    }

    public function isActive()
    {
        if (Request::env('SESSION')->id->exists()
            && Request::env('SESSION')->token->eq(Token::get(Request::env('SESSION')->id->val()))) {
            return true;
        }
        return false;
    }
}
