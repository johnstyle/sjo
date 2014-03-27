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
    public function edit (MysqlObject $instance)
    {
        if (Request::env('REQUEST')->{$instance->getPrimaryKey()}->exists()) {
            $instance->setPrimaryValue(Request::env('REQUEST')->{$instance->getPrimaryKey()}->val());
        }

        $this->update($instance);
    }

    public function update (MysqlObject $instance)
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

            if ($this->validate($instance)) {

                if($instance->save()){
                    Alert::set(Lib\I18n::__('The item has been saved.'), 'success');
                }

                switch ($next) {

                    default:
                        Http::redirect(Router::linkBack(Router::$controller));
                        break;

                    case 'stay':
                        Http::redirect(Router::linkBack(null, array($instance->getPrimaryKey() => $instance->getPrimaryValue())));
                        break;

                    case 'create':
                        Http::redirect(Router::linkBack(null));
                        break;
                }
            }
        }
    }

    public function delete (MysqlObject $instance)
    {
        if (Request::env('REQUEST')->{$instance->getPrimaryKey()}->val()) {
            if($instance->delete()) {
                Alert::set(Lib\I18n::__('The item has been deleted.'), 'success');
            }
        }

        Http::redirect(Router::linkBack(Router::$controller));
    }
}
