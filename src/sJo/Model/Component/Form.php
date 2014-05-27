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

use sJo\Data\Validate;
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
    /** @var   */
    protected $__form;
    protected $__formHash;

    /**
     * @param array $fields
     * @param array $form
     *
     * @return $this
     */
    public function quickForm (array $fields = null, array $form = array())
    {
        $this->createForm($fields, $form);
        $this->updateForm();

        return $this;
    }

    /**
     * @param array $fields
     * @param array $form
     *
     * @return $this
     */
    public function setFormDefinition (array $fields = null, array $form = array())
    {
        if ($fields) {

            $includeFields = array();
            $excludeFields = array();

            foreach ($fields as $field) {

                if (preg_match("#^!(.+)$#", $field, $match)) {

                    array_push($excludeFields, $match[1]);

                } else {

                    array_push($includeFields, $field);
                }
            }

            foreach ($this->__form['fields'] as $name=>$attr) {

                if (in_array($name, $excludeFields)
                    || (count($includeFields) && !in_array($name, $includeFields))) {

                    unset($this->__form['fields'][$name]);
                }
            }
        }

        if (count($form)) {

            $this->__form = Arr::extend($this->__form, $form);
        }

        return $this;
    }

    public function getFormDefinition ()
    {
        return $this->__form;
    }

    /**
     * @param array $fields
     * @param array $form
     *
     * @return $this
     */
    public function createForm (array $fields = null, array $form = array())
    {
        $this->setFormDefinition($fields, $form);

        if (isset($this->__form['i18n']['header'])
            && !is_array($this->__form['i18n']['header'])) {

            $this->__form['i18n']['header'] = array(
                'create' => $this->__form['i18n']['header'],
                'edit' => $this->__form['i18n']['header']
            );
        }

        $this->__form = Arr::extend(array(
            'grid' => null,
            'useAlert' => true,
            'fields' => null,
            'i18n' => array(
                'header' => array(
                    'create' => I18n::__('Create'),
                    'edit' => I18n::__('Edit'),
                ),
                'actions' => array(
                    'saved' => I18n::__('The item has been saved.'),
                    'deleted' => I18n::__('The item has been deleted.'),
                ),
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

        $this->__formHash = Arr::md5($this->__form);

        return $this;
    }

    /**
     *
     */
    public function render ()
    {
        $fields = array(
            Html\Token::create(Router::getToken(null, $this->__formHash))
        );

        if (true === $this->__form['useAlert']) {

            $fields[] = Bootstrap\Alert::create(array(
                'formHash' => $this->__formHash
            ));
        }

        if ($this->__form['fields']) {

            foreach ($this->__form['fields'] as $name=>$field) {

                $field = Arr::extend(array(
                    'name' => $name,
                    'value' => $this->request($name),
                    'alert' => $this->inError($name) ? 'error' : null,
                    'icon' => $this->inError($name) ? 'remove' : null
                ), $field);

                $fields[] = Bootstrap\Field::create($field);
            }
        }

        Bootstrap\Panel::create(array(
            'grid' => $this->__form['grid'],
            'header' => $this->getPrimaryValue() ? $this->__form['i18n']['header']['edit'] : $this->__form['i18n']['header']['create'],
            'class' => $this->getPrimaryValue() ? 'panel-primary' : 'panel-success',
            'main' => Bootstrap\Fieldset::create($fields),
            'footer' => Bootstrap\ButtonGroup::create(array(
                'class' => 'pull-right',
                'elements' => $this->__form['buttons']
            ))
        ))->render();
    }

    /**
     *
     */
    protected function editForm ()
    {
        $this->setPrimaryValue(Request::env('REQUEST')->{$this->getPrimaryKey()}->val());

        $this->updateForm();
    }

    /**
     * @param bool $redirect
     * @param bool $displayAlert
     *
     * @return bool
     */
    public function updateForm ($redirect = true, $displayAlert = true)
    {
        $success = false;

        if ($this->isSubmitedForm()) {

            $this->assignFormValues();

            if ($this->validate()) {

                $success = $this->save();

                if (method_exists($this, 'saveForm')) {
                    $success &= $this->saveForm();
                }

                if($success && $displayAlert){

                    Alert::set($this->__form['i18n']['actions']['saved'], 'success', $this->__formHash);
                }

                if ($redirect) {

                    $this->redirectForm();
                }
            }
        }

        return $success;
    }

    /**
     * @param bool $redirect
     * @param bool $displayAlert
     *
     * @return bool
     */
    public function deleteForm ($redirect = true, $displayAlert = true)
    {
        $success = false;

        if ($this->isSubmitedForm('REQUEST')) {

            if (Request::env('REQUEST')->{$this->getPrimaryKey()}->val()) {

                $success = $this->delete();

                if($success && $displayAlert){

                    Alert::set($this->__form['i18n']['actions']['deleted'], 'success', $this->__formHash);
                }
            }

            if ($redirect) {

                $this->redirectForm();
            }
        }

        return $success;
    }

    /**
     *
     */
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

    /**
     * @param string $type
     *
     * @return bool
     */
    public function isSubmitedForm ($type = 'POST')
    {
        return Request::env($type)->exists()
            && Token::has($type);
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function saveFormField ($name)
    {
        return true;
    }

    /**
     * @return bool
     */
    public function assignFormValues ($type = 'POST')
    {
        foreach (Request::env($type)->getArray() as $name => $value) {

            $this->setTableColumnsValue($name, $value);
        }
    }

    /**
     * @return array
     */
    public function getFormFields ()
    {
        return $this->__form['fields'];
    }

    /**
     * @param      $name
     * @param null $key
     *
     * @return null
     */
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

    /**
     * @return bool
     */
    protected function validateForm ()
    {
        foreach ($this->getFormFields() as $name => $attr) {

            // Get field label
            $label = $this->getFieldLabel($name);

            if (isset($attr['validate'])) {

                if (Validate::isCallable($attr['validate'])) {

                    call_user_func($attr['validate'], $attr, $name, $label);

                } elseif (preg_match("#^:([[:alnum:]]+)$#", $attr['validate'], $match)) {

                    if (Request::env('POST')->{$name}->val() !== Request::env('POST')->{$match[1]}->val()) {

                        $this->setError($name, I18n::__(
                            'The field %s must be identical to %s.',
                            '<strong>' . $label . '</strong>',
                            '<strong>' . $this->getFieldLabel($match[1]) . '</strong>'
                        ));
                    }
                }
            }
        }

        return !$this->hasErrors();
    }
}
