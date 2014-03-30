<?php

namespace sJo\Controller;

use sJo\Loader\Alert;
use sJo\Loader\Router;
use sJo\Model\MysqlObject;
use sJo\Libraries as Lib;
use sJo\Http\Http;
use sJo\Request\Request;

trait Action
{
    protected function getId (MysqlObject $instance)
    {
        if (!$this->isCreate($instance)) {

            return Request::env('REQUEST')->{$instance->getPrimaryKey()}->val();
        }

        return null;
    }

    protected function isCreate (MysqlObject $instance)
    {
        return !Request::env('REQUEST')->{$instance->getPrimaryKey()}->exists();
    }

    protected function edit (MysqlObject $instance, callable $callback = null)
    {
        $instance->setPrimaryValue($this->getId($instance));

        $this->update($instance, $callback);
    }

    protected function update (MysqlObject $instance, callable $callback = null)
    {
        if (!Request::env('POST')->exists()) {
            return;
        }

        $next = 'back';
        if(Request::env('POST')->saveAndStay->exists()) {
            $next = 'stay';
        } elseif (Request::env('POST')->saveAndCreate->exists()) {
            $next = 'create';
        }

        unset(Request::env('POST')->controller);
        unset(Request::env('POST')->method);
        unset(Request::env('POST')->token);
        unset(Request::env('POST')->saveAndStay);
        unset(Request::env('POST')->saveAndCreate);
        unset(Request::env('POST')->saveAndBack);

        foreach (Request::env('POST')->getArray() as $name => $value) {
            $instance->{$name} = $value;
        }

        if ($this->validate($instance)) {

            if($instance->save()){

                if ($callback) {
                    call_user_func($callback, $instance);
                }

                Alert::set(Lib\I18n::__('The item has been saved.'), 'success');
            }

            switch ($next) {

                default:
                    Http::redirect(Router::link(null, Router::$controller));
                    break;

                case 'stay':
                    Http::redirect(Router::link(null, null, array($instance->getPrimaryKey() => $instance->getPrimaryValue())));
                    break;

                case 'create':
                    Http::redirect(Router::link());
                    break;
            }
        }
    }

    protected function delete (MysqlObject $instance, callable $callback = null)
    {
        if (Request::env('REQUEST')->{$instance->getPrimaryKey()}->val()) {

            if($instance->delete()) {

                if ($callback) {
                    call_user_func($callback, $instance);
                }

                Alert::set(Lib\I18n::__('The item has been deleted.'), 'success');
            }
        }

        Http::redirect(Router::link(null, Router::$controller));
    }
}
