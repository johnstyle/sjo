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

namespace sJo\Controller\Component;

use sJo\Loader\Token;
use sJo\Libraries as Lib;
use sJo\Libraries\Http\Http;
use sJo\Loader\Router;

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
    public static $id;

    /**
     * @param bool $close
     * @return bool
     */
    public static function start($close = true)
    {
        if(!headers_sent()) {

            if (session_status() === PHP_SESSION_NONE) {

                session_start();

                if (session_status() === PHP_SESSION_ACTIVE) {

                    self::$id = session_id();

                    if($close) {

                        session_write_close();
                    }

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param callable $callback
     * @param array $args
     */
    public static function write(callable $callback, array $args = array())
    {
        if(is_callable($callback)){

            if (self::start(false)) {

                call_user_func_array($callback, $args);
                session_write_close();
            }
        }
    }

    public function check($auth)
    {
        self::start();

        if (Router::$controller !== $auth) {
            if (!$this->isActive()) {
                http_response_code(401);
                $this->redirect(Router::link($auth, array('redirect' => Lib\Env::server('REQUEST_URI'))));
            }
        } elseif ($this->isActive() && !Router::$method) {
            $this->redirect();
        }
    }

    public function signin($id, $url = SJO_BASEHREF)
    {
        Lib\Env::sessionSet('id', $id);
        Lib\Env::sessionSet('token', Token::get($id));
        $this->redirect($url);
    }

    public function signout($url = SJO_BASEHREF)
    {
        if (Lib\Env::cookieExists()) {
            foreach (Lib\Env::cookie() as $name => $value) {
                Lib\Env::cookieSet($name);
            }
        }

        if (self::start(false)) {
            session_destroy();
        }

        $this->redirect($url);
    }

    public function redirect($url = SJO_BASEHREF)
    {
        if (preg_match("#^(\./|/)#", Lib\Env::get('redirect'))) {
            Http::redirect(Lib\Env::get('redirect'));
        } else {
            Http::redirect($url);
        }
    }

    public function isActive()
    {
        if (Lib\Env::session('id')
            && Lib\Env::session('token') === Token::get(Lib\Env::session('id'))) {
            return true;
        }
        return false;
    }
}
