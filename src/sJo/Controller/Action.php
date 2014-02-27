<?php

namespace sJo\Controller;

use sJo\Loader\Alert;
use sJo\Loader\Router;
use sJo\Model\MysqlObject;
use sJo\Libraries as Lib;
use sJo\Http\Http;

trait Action
{
    private $error;

    protected function edit (MysqlObject $instance)
    {
        if (Lib\Env::requestExists($instance->getPrimaryKey())) {
            $instance->setPrimaryValue(Lib\Env::request($instance->getPrimaryKey()));
        }

        $this->update($instance);
    }

    protected function update (MysqlObject $instance)
    {
        if (Lib\Env::postExists()) {

            $stay = false;
            if(Lib\Env::postExists('stay')) {
                $stay = true;
            }

            Lib\Env::postSet('controller');
            Lib\Env::postSet('method');
            Lib\Env::postSet('token');
            Lib\Env::postSet('stay');

            foreach (Lib\Env::post() as $name => $value) {
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
                if ($stay) {
                    Http::redirect(Router::link(null, array($instance->getPrimaryKey() => $instance->getPrimaryValue())));
                } else{
                    Http::redirect(Router::link(Router::$controller));
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
