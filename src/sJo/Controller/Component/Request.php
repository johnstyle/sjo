<?php

/**
 * Gestion des requêtes
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

use sJo\Object\Singleton;
use sJo\Libraries as Lib;
use sJo\Loader\Router;

/**
 * Gestion des requêtes
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */
class Request
{
    public function getToken($method)
    {
        Session::start();

        return Session::getToken(Session::$id  . $method);
    }

    public function hasToken()
    {
        $controller = str_replace('\\', '/', Router::$controller);
        if (Lib\Env::request('token') == $this->getToken(
                $controller . Router::$__map['method']['separator'] . Router::$method
            )
        ) {
            return true;
        }
        return false;
    }

    public function filter($key)
    {
        return Lib\Arr::getTree(Lib\Env::get('filters'), $key);
    }

    public static function redirect($url = false)
    {
        header('Location:' . ($url ? $url : './'));
        exit;
    }
}
