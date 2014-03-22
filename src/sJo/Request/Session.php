<?php

/**
 * Gestion des sesions de connexion
 *
 * PHP version 5
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */

namespace sJo\Request;

use sJo\Exception\Exception;
use sJo\Libraries as Lib;

/**
 * Gestion des sesions de connexion
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */
class Session
{
    /** @var boolean $initialised */
    private static $initialised;
    /** @var $_SESSION $reference */
    public static $reference;

    /**
     * @return bool
     */
    public static function start()
    {
        if (!self::$initialised) {

            session_cache_limiter('');
            ini_set('session.use_cookies', 1);

            if (version_compare(phpversion(), '5.4.0', '>=')) {
                session_register_shutdown();
            } else {
                register_shutdown_function('session_write_close');
            }

            self::$initialised = true;
        }

        if (session_status() === PHP_SESSION_DISABLED) {
            Exception::error(Lib\I18n::__('PHP sessions are disabled.'));
        }

        if (session_status() === PHP_SESSION_ACTIVE) {
            return true;
        }

        if (ini_get('session.use_cookies') && headers_sent($file, $line)) {
            Exception::error(Lib\I18n::__(
                'Failed to start the session because headers have already been sent by "%s" at line %d.',
                $file,
                $line
            ));
        }

        if (!session_start()) {
            Exception::error(Lib\I18n::__('Failed to start the session.'));
        }

        self::$reference =& $_SESSION;

        return true;
    }

    /**
     * @param callable $callback
     * @param array $args
     * @return mixed|null
     */
    public static function write(callable $callback, array $args = array())
    {
        $success = false;

        if(is_callable($callback)){

            if (self::start(false)) {

                call_user_func_array($callback, $args);
                session_write_close();
                $success = true;
            }
        }

        return $success;
    }

    public static function id()
    {
        if (self::start()) {

            return session_id();
        }

        return null;
    }

    public static function destroy()
    {
        if (self::start(false)) {

            session_destroy();
        }
    }
}
