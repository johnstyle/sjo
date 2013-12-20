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

namespace sJo;

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

        return Session::getToken(session_id() . $method);
    }

    public function hasToken()
    {
        $controller = str_replace('\\', '/', Loader::$controller);
        if (Libraries\Env::request('token') == $this->getToken(
                $controller . SJO_CONTROLLER_METHOD_SEPARATOR . Loader::$method
            )
        ) {
            return true;
        }
        return false;
    }

    public function filter($key)
    {
        return Libraries\Arr::getTree(Libraries\Env::get('filters'), $key);
    }

    public static function redirect($url = false)
    {
        header('Location:' . ($url ? $url : './'));
        exit;
    }
}
