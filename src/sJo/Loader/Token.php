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
use sJo\Request\Request;
use sJo\Request\Session;

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
        return Encrypter::md5(
              Request::env('SERVER')->REMOTE_ADDR->val()
            . Request::env('SERVER')->HTTP_USER_AGENT->val()
            . Request::env('SERVER')->HTTP_HOST->val()
            . Session::id()
            . $value
        );
    }

    public static function has()
    {
        if (Request::env('REQUEST')->token->eq(self::get(Router::getToken()))) {
            return true;
        }
        return false;
    }
}
