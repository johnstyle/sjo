<?php

/**
 * Gestion des Controlleurs
 *
 * PHP version 5
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */

namespace sJo\Controller;

use sJo\Loader\Router;
use sJo\Loader\Logger;
use sJo\Request\Request;
use sJo\Http\Http;
use sJo\Loader\Token;

/**
 * Gestion des Controlleurs
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */
trait Auth
{
    public function signout ()
    {
        Logger::getInstance()->info('Signout {token}', array(
            'token' => self::session()->token->val()
        ));

        self::cookie()->destroy();
        self::session()->destroy();

        Http::redirect(Router::link());
    }

    public static function secure ()
    {
        if (Router::$controller !== self::$authModel . '\\Auth') {

            if (!self::isLogged()) {

                http_response_code(401);

                Http::redirect(Router::link(Router::$interface, self::$authModel . '\\Auth', array(
                    'redirect' => Request::env('SERVER')->REQUEST_URI->val())
                ));
            }

        } elseif (self::isLogged() && !Router::$method) {

            Http::redirect(Router::link());
        }
    }

    public static function isLogged ()
    {
        if (self::session()->id->exists()
            && self::session()->token->eq(Token::get(self::session()->id->val()))) {

            return true;
        }

        return false;
    }

    public static function session ()
    {
        return Request::env('SESSION')->{self::$authModel};
    }

    public static function cookie ()
    {
        return Request::env('COOKIES')->{self::$authModel};
    }
}
