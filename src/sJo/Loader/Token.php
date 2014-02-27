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

namespace sJo\Loader;

use sJo\Encryption\Encrypter;
use sJo\Libraries\Env;
use sJo\Controller\Component\Session;

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
    public static function get($value = null)
    {
        Session::start();

        return Encrypter::md5(
            Env::server('REMOTE_ADDR')
            . Env::server('HTTP_USER_AGENT')
            . Env::server('HTTP_HOST')
            . Session::$id  . $value
        );
    }

    public static function has()
    {
        if (Env::request('token') == self::get(Router::getToken())) {
            return true;
        }
        return false;
    }
}
