<?php

namespace sJo\Core\Controller;

use sJo\Libraries as Lib;

trait Action
{
    public function update ($className)
    {
        if ($post = Lib\Env::post()) {
            unset($post['controller']);
            unset($post['method']);
            unset($post['token']);
            $instance = new $className();
            foreach ($post as $name => $value) {
                $instance->{$name} = $value;
            }
            $instance->save();
        }
    }

    public function delete ($className)
    {
        $instance = new $className();
        $pk = $instance->getPrimaryKey();
        if ($id = Lib\Env::post($pk)) {
            $instance->delete($id);
        }
    }
}
