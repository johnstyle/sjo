<?php

/**
 * Gestion des sesions de connexion
 *
 * PHP version 5
 *
 * @package  PHPTools
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/PHPTools.git
 */

namespace PHPTools;

/**
 * Gestion des sesions de connexion
 *
 * @package  PHPTools
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/PHPTools.git
 */
class Session
{
    private static $id;

    public static function start()
    {
        if (session_status() == PHP_SESSION_NONE) {

            session_start();

            if (!self::$id) {

                self::$id = session_id();

                session_write_close();
            }
        }
    }

    public function check()
    {
        self::start();

        if (CONTROLLER != PHPTOOLS_CONTROLLER_AUTH) {
            if (!$this->isActive()) {
                if (Libraries\Env::get('token')) {
                    $this->isActive(Libraries\Env::get('token'));
                } else {
                    http_response_code(401);
                    $this->redirect(
                        PHPTOOLS_BASEHREF . '/' . PHPTOOLS_CONTROLLER_AUTH . '/?redirect=' . urlencode(
                            Libraries\Env::server('REQUEST_URI')
                        )
                    );
                }
            }
        } elseif ($this->isActive() && !METHOD) {
            $this->redirect();
        }
    }

    public function signin($token, $url = PHPTOOLS_BASEHREF)
    {
        Libraries\Env::sessionSet('token', $token);
        $this->redirect($url);
    }

    public function signout($url = PHPTOOLS_BASEHREF)
    {
        if (Libraries\Env::cookie()) {
            foreach (Libraries\Env::cookie() as $name => $value) {
                Libraries\Env::cookieSet($name);
            }
        }
        session_destroy();
        $this->redirect($url);
    }

    public function redirect($url = PHPTOOLS_BASEHREF)
    {
        if (preg_match("#^(\./|/)#", Libraries\Env::get('redirect'))) {
            header('Location:' . Libraries\Env::get('redirect'));
        } else {
            header('Location:' . $url);
        }
        exit;
    }

    public function isActive()
    {
        if (Libraries\Env::session('token')) {
            return true;
        }
        return false;
    }

    public static function getToken($options = false)
    {
        return md5(
            PHPTOOLS_SALT
            . Libraries\Env::server('REMOTE_ADDR')
            . Libraries\Env::server('HTTP_USER_AGENT')
            . Libraries\Env::server('HTTP_HOST')
            . $options
        );
    }
}
