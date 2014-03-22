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
    private $error;

    protected function edit (MysqlObject $instance)
    {
        if (Request::env('REQUEST')->{$instance->getPrimaryKey()}->exists()) {
            $instance->setPrimaryValue(Request::env('REQUEST')->{$instance->getPrimaryKey()}->val());
        }

        $this->update($instance);
    }

    protected function update (MysqlObject $instance)
    {
        if (Request::env('POST')->exists()) {

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

            foreach ($instance->getTableFields() as $name => $attr) {
                if(isset($attr['required']) && $attr['required'] && !$instance->{$name}) {
                    if(isset($attr['default'])) {
                        $instance->{$name} = $attr['default'];
                    } else {
                        $this->error = Alert::set(Lib\I18n::__('The field %s is required.', $name));
                    }
                }
            }

            if (!$this->error) {

                if($instance->save()){
                    Alert::set(Lib\I18n::__('The item has been saved.'), 'success');
                }

                switch ($next) {

                    default:
                        Http::redirect(Router::link(Router::$controller));
                        break;

                    case 'stay':
                        Http::redirect(Router::link(null, array($instance->getPrimaryKey() => $instance->getPrimaryValue())));
                        break;

                    case 'create':
                        Http::redirect(Router::link(null));
                        break;
                }
            }
        }
    }

    protected function delete (MysqlObject $instance)
    {
        if (Lib\Env::requestExists($instance->getPrimaryKey())) {
            if($instance->delete()) {
                Alert::set(Lib\I18n::__('The item has been deleted.'), 'success');
            }
        }
        Http::redirect(Router::link(Router::$controller));
    }
}
