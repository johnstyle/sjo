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

namespace sJo\Model\Component;

use sJo\Request\Request;
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
    public static function isLogged ()
    {
        if (static::session()->{static::getInstance()->getPrimaryKey()}->exists()
            && static::session()->token->eq(Token::get(static::getSessionId()))) {

            return true;
        }

        return false;
    }

    public static function getSessionId ()
    {
        return static::session()->{static::getInstance()->getPrimaryKey()}->val();
    }

    public static function session ()
    {
        return Request::env('SESSION')->{__CLASS__};
    }

    public static function cookie ()
    {
        return Request::env('COOKIES')->{__CLASS__};
    }
}
