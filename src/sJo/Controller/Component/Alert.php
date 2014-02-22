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

namespace sJo\Controller\Component;

use sJo\Object\Singleton;
use sJo\Libraries as Lib;

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

        if (Lib\Env::sessionExists('alerts')) {
            self::$alerts = json_decode(Lib\Env::session('alerts'), true);
        }
    }

    public function __destruct()
    {
        if(self::$alerts) {
            Lib\Env::sessionSet('alerts', json_encode(self::$alerts));
        } else {
            Lib\Env::sessionSet('alerts');
        }
    }

    public function get()
    {
        $alert = self::$alerts;
        Lib\Env::sessionSet('alerts');
        self::$alerts = null;
        return $alert;
    }

    public function set($message, $type = 'danger')
    {
        self::$alerts[$type][] = $message;
    }

    public function exists()
    {
        if (self::$alerts) {
            return true;
        }
        return false;
    }
}
