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

use sJo\Controller\Controller;
use sJo\Exception\Exception;
use sJo\Model\Component\Event;
use sJo\Libraries as Lib;
use sJo\Helpers;
use sJo\Module\Module;
use sJo\Request\Request;
use sJo\View\View;
use sJo\File\File;

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

    public static $root;

    private $view;
    private $alert;
    private $router;
    private $module;
    private $instance;

    /**
     * Constructeur
     *
     * @return \sJo\Loader\Loader
     */
    public function __construct()
    {
        self::$root = realpath(dirname(__DIR__));

        /** Load Settings */
        Lib\Ini::load()
            ->file(self::$root . '/settings.default.ini')
            ->toDefine();

        if (defined('SJO_TIMEZONE')) {
            date_default_timezone_set(SJO_TIMEZONE);
        }

        /** Locale */
        $sJo_I18n = new Lib\I18n();
        $sJo_I18n->load('default', self::$root . '/Locale');

        /** Router */
        $this->router = new Router($this);

        /** Alert */
        $this->alert = new Alert();

        /** App autoload */
        Helpers\Autoload(SJO_ROOT_APP);

        /** Bootstrap */
        File::__include(SJO_ROOT_APP . '/' . Router::$interface . '/bootstrap.php');

        /** Load modules */
        $this->module = new Module();
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

                if ($this->instance instanceof Controller) {

                    $this->event('preloadView');

                    $this->view = new View($this->instance);

                    $this->event('initView');

                } else {
                    $this->errorDocument('http403', Lib\I18n::__('Controller %s is not extended to %s.', Router::$controllerClass, '\\sJo\\Controller'));
                }
            } else {
                $this->errorDocument('http404', Lib\I18n::__('Controller %s do not exists.', Router::$controllerClass));
            }
        } else {
            $this->errorDocument('http404', Lib\I18n::__('CONTROLLER is undefined.'));
        }

        return $this;
    }

    public function display()
    {
        $render = null;

        if (Router::$method) {

            switch (Request::env('GET')->content_type->val()) {

                case 'json' :
                    header('Content-type:application/json; charset=' . SJO_CHARSET);
                    if (method_exists(Router::$controllerClass, Router::$method)) {
                        if (Token::has()) {
                            echo json_encode($this->instance->{Router::$method}());
                        } else {
                            $this->errorDocument('http403', Lib\I18n::__('Warning ! Prohibited queries.'));
                        }
                    }
                    exit;
                    break;

                default :
                    header('Content-type:text/html; charset=' . SJO_CHARSET);
                    if (method_exists(Router::$controllerClass, Router::$method)) {
                        if(Request::env('POST')->exists()) {
                            if (Token::has()) {
                                $render = $this->instance->{Router::$method}();
                            } else {
                                $this->errorDocument('http403', Lib\I18n::__('Warning ! Prohibited queries.'));
                            }
                        } else {
                            $render = $this->instance->{Router::$method}();
                        }
                    }
                    break;
            }
        }

        $this->event('loadedView');
        $this->view->display($render);
        $this->event('displayedView');
    }

    private function errorDocument ($controller, $msg, $code = 0)
    {
        Router::errorDocument($controller);
        new Exception($msg, $code);
        $this->init();
        $this->instance->message = $msg;
    }
}
