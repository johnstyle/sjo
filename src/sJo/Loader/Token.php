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

namespace sJo\Controller;

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
class Token
{
    public static function get($method = null)
    {
        Component\Session::start();

        return Lib\Crypto\Crypto::md5(
            Lib\Env::server('REMOTE_ADDR')
            . Lib\Env::server('HTTP_USER_AGENT')
            . Lib\Env::server('HTTP_HOST')
            . Component\Session::$id  . $method
        );
    }

    public static function has()
    {
        if (Lib\Env::request('token') == self::get(Router::getToken())) {
            return true;
        }
        return false;
    }
}
