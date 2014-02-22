<?php

/**
 * Loader
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

use sJo\Exception\Exception;
use sJo\Object\Event;
use sJo\Libraries as Lib;
use sJo\Helpers;
use sJo\Module\Module;
use sJo\View\View;

/**
 * Loader
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */
class Loader
{
    use Event;

    private $root;

    /**
     * Constructeur
     *
     * @return \sJo\Loader\Loader
     */
    public function __construct()
    {
        $this->root = dirname(realpath(__DIR__));

        /** Load Settings */
        Lib\Ini::load()
            ->file($this->root . '/settings.default.ini')
            ->toDefine();

        if (defined('SJO_TIMEZONE')) {
            date_default_timezone_set(SJO_TIMEZONE);
        }

        /** Locale */
        $sJo_I18n = new Lib\I18n();
        $sJo_I18n->load('default', $this->root . '/Locale');

        /** Router */
        new Router($this);

        /** Bootstrap */
        Lib\File::__include(SJO_ROOT_APP . '/' . Router::$interface . '/bootstrap.php');

        /** App autoload */
        Helpers\Autoload(SJO_ROOT_APP);

        /** Load modules */
        new Module();
    }

    public static function quickStart()
    {
        $Loader = new self ();
        $Loader->init();
        $Loader->display();

        return $Loader;
    }

    public function init()
    {
        if (Router::$controller) {

            if (class_exists(Router::$controllerClass)) {

                $this->instance = new Router::$controllerClass ();

                $this->event('initController');

                if (get_parent_class($this->instance) == 'sJo\\Controller\\Controller') {

                    $this->event('preloadView');

                    new View($this->instance);

                    $this->event('initView');

                } else {
                    $this->ErrorDocument('http403', Lib\I18n::__('Controller %s is not extended to %s.', Router::$controllerClass, '\\sJo\\Controller'));
                }
            } else {
                $this->ErrorDocument('http404', Lib\I18n::__('Controller %s do not exists.', Router::$controllerClass));
            }
        } else {
            $this->ErrorDocument('http404', Lib\I18n::__('CONTROLLER is undefined.'));
        }

        return $this;
    }

    public function display()
    {
        if (Router::$method) {
            switch (Lib\Env::get('content_type')) {
                case 'json' :
                    header('Content-type:application/json; charset=' . SJO_CHARSET);
                    if (method_exists(Router::$controllerClass, Router::$method)) {
                        if ($this->instance->component->request->hasToken()) {
                            echo json_encode($this->instance->{Router::$method}());
                        } else {
                            $this->ErrorDocument('http403', Lib\I18n::__('Warning ! Prohibited queries.'));
                        }
                    }
                    exit;
                    break;
                default :
                    header('Content-type:text/html; charset=' . SJO_CHARSET);
                    if (method_exists(Router::$controllerClass, Router::$method)) {
                        if ($this->instance->component->request->hasToken()) {
                            $this->instance->{Router::$method}();
                        } else {
                            $this->ErrorDocument('http403', Lib\I18n::__('Warning ! Prohibited queries.'));
                        }
                    }
                    break;
            }
        }

        $this->event('loadedView');

        View::display();

        $this->event('displayedView');
    }

    private function ErrorDocument ($controller, $msg, $code = 0)
    {
        Router::ErrorDocument($controller);
        new Exception($msg, $code);
        $this->init();
        $this->instance->message = $msg;
    }
}
