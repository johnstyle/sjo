<?php

namespace sJo\Controller;

use sJo\Model\MysqlObject;
use sJo\Libraries as Lib;

trait Action
{
    private $error;

    protected function create (MysqlObject $instance)
    {
        if ($post = Lib\Env::post()) {

            unset($post['controller']);
            unset($post['method']);
            unset($post['token']);

            foreach ($post as $name => $value) {
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
            }
        }
    }

    protected function update (MysqlObject $instance)
    {
        $pk = $instance->getPrimaryKey();

        if ($instance->{$pk} || Lib\Env::post($pk)) {
            $this->create($instance);
        }
    }

    protected function delete (MysqlObject $instance)
    {
        $pk = $instance->getPrimaryKey();

        if ($id = Lib\Env::post($pk)) {
            $instance->delete($id);
        }
    }
}
