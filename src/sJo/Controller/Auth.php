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
            'token' => self::model()->session()->token->val()
        ));

        self::model()->cookie()->destroy();
        self::model()->session()->destroy();

        Http::redirect(Router::link());
    }

    public static function secure ()
    {
        $rc = new \ReflectionClass(self::model());
        $modelName = $rc->getShortName();

        if (Router::$controller !== $modelName . '\\Auth') {

            if (!self::model()->isLogged()) {

                http_response_code(401);

                Http::redirect(Router::link(Router::$interface, $modelName . '\\Auth', array(
                    'redirect' => Request::env('SERVER')->REQUEST_URI->val())
                ));
            }

        } elseif (self::model()->isLogged() && !Router::$method) {

            Http::redirect(Router::link());
        }
    }
}
