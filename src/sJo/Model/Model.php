<?php

namespace sJo\Model;

use sJo\Model\Database\DatabaseInterface;
use sJo\Object\Singleton;
use sJo\Object\Entity;
use sJo\Model\Database\Map;

abstract class Model implements DatabaseInterface
{
    use Singleton;
    use Entity;
    use Control\Format;
    use Control\Error;
    use Control\Validate {
        Control\Validate::validate as private validateControl;
    }
    use Map {
        Map::__construct as private __constructMap;
    }

    public function __construct($id = null)
    {
        if (null !== $id) {

            $this->setPrimaryValue($id);
        }

        $this->__constructMap();
    }

    public function secure (array $fields)
    {
        $success = true;

        if (null !== $this->getPrimaryValue()) {

            foreach ($fields as $name=>$value) {

                if ($this->{$name} !== $value) {

                    $success = false;
                }
            }
        }

        return $success;
    }

    public function validate ()
    {
        $this->validateControl();

        if (method_exists($this, 'validateForm')) {

            $this->validateForm();
        }

        return !$this->hasErrors();
    }

    protected function getFieldLabel ($name)
    {
        // Get field label
        $label = null;

        if (method_exists($this, 'getFormFieldDefinition')) {

            $label = $this->getFormFieldDefinition($name, 'label');
        }

        if (is_null($label)) {

            $label = $name;
        }

        return $label;
    }
}
