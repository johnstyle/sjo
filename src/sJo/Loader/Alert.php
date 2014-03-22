<?php

/**
 * Alertes
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

use sJo\Object\Singleton;
use sJo\Libraries as Lib;
use sJo\Request\Request;
use sJo\Request\Session;

/**
 * Alertes
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */
class Alert
{
    private static $alerts;

    public function __construct()
    {
        Session::start();

        if (Request::env('SESSION')->alerts->exists()) {
            self::$alerts = json_decode(Request::env('SESSION')->alerts->val(), true);
        }
    }

    public function __destruct()
    {
        if(self::$alerts) {
            Request::env('SESSION')->alerts = json_encode(self::$alerts);
        } else {
            unset(Request::env('SESSION')->alerts);
        }
    }

    public static function get()
    {
        $alert = self::$alerts;
        unset(Request::env('SESSION')->alerts);
        self::$alerts = null;
        return $alert;
    }

    public static function set($message, $type = 'danger')
    {
        self::$alerts[$type][] = $message;

        return true;
    }

    public static function exists()
    {
        if (self::$alerts) {
            return true;
        }
        return false;
    }
}
