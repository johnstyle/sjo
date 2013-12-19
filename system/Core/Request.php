<?php

/**
 * Gestion des requêtes
 *
 * PHP version 5
 *
 * @package  PHPTools
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/PHPTools.git
 */

namespace PHPTools;

/**
 * Gestion des requêtes
 *
 * @package  PHPTools
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/PHPTools.git
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
                $controller . PHPTOOLS_CONTROLLER_METHOD_SEPARATOR . Loader::$method
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
