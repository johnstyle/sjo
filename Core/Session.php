<?php
/**
 * PHPTools
 *
 * PHP version 5
 *
 * @package  PHPTools
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/PHPTools.git
 */

namespace PHPTools;

class Session
{
    public static function start ()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function check ()
    {
        self::start();

        if (CONTROLLER != PHPTOOLS_CONTROLLER_AUTH) {
            if (!$this->isActive()) {
                if (Libraries\Env::get('token')) {
                    $this->isActive(Libraries\Env::get('token'));
                } else {
                    http_response_code(401);
                    $this->redirect('./?' . PHPTOOLS_CONTROLLER_NAME . '=' . PHPTOOLS_CONTROLLER_AUTH . '&redirect=' . urlencode('/?' . Libraries\Env::server('QUERY_STRING')));
                }
            }
        } elseif ($this->isActive() && !METHOD) {
            $this->redirect();
        }
    }

    public function signin ($token)
    {
        Libraries\Env::sessionSet('token', $token);
        $this->redirect();
    }

    public function signout ($url = './')
    {
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        $this->redirect($url);
    }

    public function redirect ($url = './')
    {
        if (Libraries\Env::get('redirect')) {
            header('Location:.' . Libraries\Env::get('redirect'));
        } else {
            header('Location:' . $url);
        }
        exit ;
    }

    public function isActive ()
    {
        if (Libraries\Env::session('token')) {
            return true;
        }
        return false;
    }

}
