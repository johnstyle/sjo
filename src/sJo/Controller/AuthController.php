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

namespace sJo\Controller;

use sJo\Http\Http;
use sJo\Libraries\I18n;
use sJo\Loader\Alert;
use sJo\Loader\Logger;
use sJo\Loader\Router;
use sJo\Loader\Token;
use sJo\Request\Request;
use sJo\View\Helper\Drivers\Bootstrap;

/**
 * Gestion des Controlleurs
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */
abstract class AuthController extends Controller
{
    public $form;

    public function index()
    {
        $model = static::model('Form');

        $this->form = $model->createForm(array(
                'email', 'password'
            ), array(
            'i18n' => array(
                'header' => I18n::__('Identification')
            ),
            'fields' => array(
                'remember' => array(
                    'type' => 'checkbox',
                    'options' => array(
                        1 => I18n::__('Garder ma session active')
                    )
                )
            ),
            'buttons' => Bootstrap\Button::create(array(
                'class' => 'btn-lg btn-block btn-primary',
                'value' => I18n::__('Login')
            )
        )));

        if ($model->isSubmitedForm()) {

            $model->assignFormValues();

            if($id = $model->exists($model->email, $model->password)) {

                Logger::getInstance()->info('Signin {user}', array(
                    'user' => $model->email
                ));

                $model->session()->id = $id;
                $model->session()->token = Token::get($id);

                $url = SJO_BASEHREF;

                if (preg_match('#^(\./|/)#', Request::env('GET')->redirect->val())) {

                    $url = Request::env('GET')->redirect->val();
                }

                Http::redirect($url);

            } else {

                Alert::set(I18n::__('Les informations de connexion sont incorrects'));
            }
        }
    }

    public function signout ()
    {
        Logger::getInstance()->info('Signout {token}', array(
            'token' => static::model('Form')->session()->token->val()
        ));

        static::model('Form')->cookie()->destroy();
        static::model('Form')->session()->destroy();

        Http::redirect(Router::link());
    }

    public static function secure ()
    {
        $controller = preg_replace('#^.+\\\\Controller\\\\#', '', get_called_class());

        if (Router::$controller !== $controller) {

            if (!static::model('Form')->isLogged()) {

                http_response_code(401);

                Http::redirect(Router::link(Router::$interface, $controller, array(
                    'redirect' => Request::env('SERVER')->REQUEST_URI->val())
                ));
            }

        } elseif (static::model('Form')->isLogged() && !Router::$method) {

            Http::redirect(Router::link());
        }
    }
}
