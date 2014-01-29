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

namespace sJo\Core;

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

        if (Loader::$controller != $auth) {
            if (!$this->isActive()) {
                if (Lib\Env::get('token')) {
                    $this->isActive(Lib\Env::get('token'));
                } else {
                    http_response_code(401);
                    $this->redirect(
                        SJO_BASEHREF . '/' . str_replace('\\', '/', $auth) . '/?redirect=' . urlencode(
                            Lib\Env::server('REQUEST_URI')
                        )
                    );
                }
            }
        } elseif ($this->isActive() && !Loader::$method) {
            $this->redirect();
        }
    }

    public function signin($token, $url = SJO_BASEHREF)
    {
        Lib\Env::sessionSet('token', $token);
        $this->redirect($url);
    }

    public function signout($url = SJO_BASEHREF)
    {
        if (Lib\Env::cookie()) {
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
            header('Location:' . Lib\Env::get('redirect'));
        } else {
            header('Location:' . $url);
        }
        exit;
    }

    public function isActive()
    {
        if (Lib\Env::session('token')) {
            return true;
        }
        return false;
    }

    public static function getToken($options = false)
    {
        return md5(
            SJO_SALT
            . Lib\Env::server('REMOTE_ADDR')
            . Lib\Env::server('HTTP_USER_AGENT')
            . Lib\Env::server('HTTP_HOST')
            . $options
        );
    }
}
