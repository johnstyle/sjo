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

namespace sJo\Model\Component;

use sJo\Http\Http;
use sJo\Libraries\Arr;
use sJo\Libraries\I18n;
use sJo\Loader\Alert;
use sJo\Loader\Router;
use sJo\Loader\Token;
use sJo\Request\Request;
use sJo\View\Helper\Drivers\Bootstrap;
use sJo\View\Helper\Drivers\Html;

/**
 * Gestion des Controlleurs
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */
trait Form
{
    protected $__form;

    public function createForm ()
    {
        $this->__form = Arr::extend(array(
            'fields' => null,
            'texts' => array(
                'create' => I18n::__('Create'),
                'edit' => I18n::__('Edit'),
                'saved' => I18n::__('The item has been saved.'),
                'deleted' => I18n::__('The item has been deleted.'),
            ),
            'buttons' =>  array(
                Bootstrap\Button::create(array(
                    'name' => '__saveAndStay',
                    'value' => I18n::__('Sauvegarder et rester')
                )),
                Bootstrap\Button::create(array(
                    'name' => '__saveAndBack',
                    'value' => I18n::__('Sauvegarder'),
                    'class' => 'btn-primary'
                ))
            )
        ), $this->__form);

        return $this;
    }

    public function render ()
    {
        $fields = array(
            Html\Token::create(Router::getToken())
        );

        if ($this->__form['fields']) {

            foreach ($this->__form['fields'] as $name=>$field) {

                $field = Arr::extend(array(
                    'name' => $name,
                    'value' => $this->request('title'),
                    'alert' => $this->inError($name) ? 'error' : null,
                    'icon' => $this->inError($name) ? 'remove' : null
                ), $field);

                $fields[] = Bootstrap\Field::create($field);
            }
        }

        Bootstrap\Panel::create(array(
            'grid' => 6,
            'header' => $this->getPrimaryValue() ? $this->__form['texts']['edit'] : $this->__form['texts']['create'],
            'class' => $this->getPrimaryValue() ? 'panel-primary' : 'panel-success',
            'main' => Bootstrap\Fieldset::create($fields),
            'footer' => Bootstrap\ButtonGroup::create(array(
                'class' => 'pull-right',
                'elements' => $this->__form['buttons']
            ))
        ))->render();
    }

    protected function editForm ()
    {
        $this->setPrimaryValue($this->getId($this));

        $this->updateForm();
    }

    public function updateForm ($redirect = true, $displayAlert = true)
    {
        $success = false;

        if ($this->isSubmitedForm()) {

            unset(Request::env('POST')->controller);
            unset(Request::env('POST')->method);
            unset(Request::env('POST')->token);

            foreach (Request::env('POST')->getArray() as $name => $value) {

                if (!preg_match("#^__#", $name)) {

                    $this->{$name} = $value;
                }
            }

            if ($this->validate()) {

                $success = $this->save();

                if (method_exists($this, 'saveForm')) {
                    $success &= $this->saveForm();
                }

                if($success
                    && $displayAlert){

                    Alert::set($this->__form['texts']['saved'], 'success');
                }

                if ($redirect) {

                    $this->redirectForm();
                }
            }
        }

        return $success;
    }

    public function deleteForm ($redirect = true, $displayAlert = true)
    {
        $success = false;

        if ($this->isSubmitedForm('REQUEST')) {

            if (Request::env('REQUEST')->{$this->getPrimaryKey()}->val()) {

                $success = $this->delete();

                if($success
                    && $displayAlert){

                    Alert::set($this->__form['texts']['deleted'], 'success');
                }
            }

            if ($redirect) {

                $this->redirectForm();
            }
        }

        return $success;
    }

    public function redirectForm ()
    {
        $next = 'back';
        if(Request::env('REQUEST')->__saveAndStay->exists()) {
            $next = 'stay';
        } elseif (Request::env('REQUEST')->__saveAndCreate->exists()) {
            $next = 'create';
        }

        switch ($next) {

            default:
                Http::redirect(Router::link(null, Router::$controller));
                break;

            case 'stay':
                Http::redirect(Router::link(null, null, array($this->getPrimaryKey() => $this->getPrimaryValue())));
                break;

            case 'create':
                Http::redirect(Router::link());
                break;
        }
    }

    protected function getId ()
    {
        if (!$this->isCreate()) {

            return Request::env('REQUEST')->{$this->getPrimaryKey()}->val();
        }

        return null;
    }

    protected function isCreate ()
    {
        return !Request::env('REQUEST')->{$this->getPrimaryKey()}->exists();
    }

    public function isSubmitedForm ($type = 'POST')
    {
        return Request::env($type)->exists()
        && Token::has($type);
    }

    public function saveFormField ($name)
    {
        return true;
    }

    public function getFormFieldDefinition ($name, $key = null)
    {
        if (isset($this->__form['fields'][$name])) {

            if ($key
                && isset($this->__form['fields'][$name][$key])) {

                return $this->__form['fields'][$name][$key];

            } else {

                return $this->__form['fields'][$name];
            }
        }

        return null;
    }
}
