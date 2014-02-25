<?php

namespace sJo\Controller;

use sJo\Loader\Router;
use sJo\Model\MysqlObject;
use sJo\Libraries as Lib;
use sJo\Libraries\Http\Http;

trait Action
{
    private $error;

    protected function create (MysqlObject $instance)
    {
        if (Lib\Env::postExists()) {

            Lib\Env::postSet('controller');
            Lib\Env::postSet('method');
            Lib\Env::postSet('token');

            foreach (Lib\Env::post() as $name => $value) {
                $instance->{$name} = $value;
            }

            foreach ($instance->getTableFields() as $name => $attr) {
                if(isset($attr['required']) && $attr['required'] && !$instance->{$name}) {
                    if(isset($attr['default'])) {
                        $instance->{$name} = $attr['default'];
                    } else {
                        $this->error = $this->component->alert->set(Lib\I18n::__('Le champ %s est requis.', $name));
                    }
                }
            }

            if (!$this->error) {
                $instance->save();
                Http::redirect(Router::link(null, array($instance->getPrimaryKey() => $instance->getPrimaryValue())));
            }
        }
    }

    protected function update (MysqlObject $instance)
    {
        if ($instance->{$instance->getPrimaryKey()}
            || Lib\Env::postExists($instance->getPrimaryKey())) {
            $this->create($instance);
        }
    }

    protected function delete (MysqlObject $instance)
    {
        if (Lib\Env::postExists($instance->getPrimaryKey())) {
            $instance->delete(Lib\Env::post($instance->getPrimaryKey()));
        }
    }
}
