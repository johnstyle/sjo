<?php

namespace sJo\Controller;

use sJo\Model\MysqlObject;
use sJo\Libraries as Lib;

trait Action
{
    public function update (MysqlObject $instance)
    {
        if ($post = Lib\Env::post()) {

            unset($post['controller']);
            unset($post['method']);
            unset($post['token']);

            foreach ($post as $name => $value) {
                $instance->{$name} = $value;
            }

            $instance->save();
        }
    }

    public function delete (MysqlObject $instance)
    {
        $pk = $instance->getPrimaryKey();

        if ($id = Lib\Env::post($pk)) {
            $instance->delete($id);
        }
    }
}
