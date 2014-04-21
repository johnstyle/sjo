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
        if (self::session()->id->exists()
            && self::session()->token->eq(Token::get(self::getId()))) {

            return true;
        }

        return false;
    }

    public static function getId ()
    {
        return self::session()->id->val();
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
