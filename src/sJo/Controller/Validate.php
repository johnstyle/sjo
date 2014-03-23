<?php

namespace sJo\Controller;

use sJo\Libraries\I18n;
use sJo\Loader\Alert;
use sJo\Model\MysqlObject;

trait Validate
{
    private $error;

    private function validate (MysqlObject $instance)
    {
        foreach ($instance->getTableFields() as $name => $attr) {

            if(isset($attr['required']) && $attr['required'] && !$instance->{$name}) {

                if(isset($attr['default'])) {
                    $instance->{$name} = $attr['default'];
                } else {
                    $this->error = Alert::set(I18n::__('The field %s is required.', $name));
                }
            }

            if ($instance->{$name}) {
                if (!\sJo\Data\Validate::is($attr['type'], $instance->{$name}, $attr['length'], $attr['values'])) {
                    $this->error = Alert::set(I18n::__(
                        'The field %s must be %s type and have maximum size %s.',
                        $name,
                        $attr['type'],
                        $attr['length']
                    ));
                }
            }
        }

        return $this->error ? false : true;
    }
}
