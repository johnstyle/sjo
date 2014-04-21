<?php

namespace sJo\Model\Control;

use sJo\Libraries\I18n;

trait Validate
{
    protected function validate ()
    {
        foreach ($this->getTableFields() as $name => $attr) {

            $label = null;

            if (method_exists($this, 'getFormFieldDefinition')) {

                $label = $this->getFormFieldDefinition($name, 'label');
            }

            if (is_null($label)) {

                $label = $name;
            }

            if(isset($attr['required'])
                && $attr['required']
                && !$this->{$name}) {

                if(isset($attr['default'])) {

                    $this->{$name} = $attr['default'];

                } else {

                    $this->setError($name, I18n::__('The field %s is required.', '<strong>' . $label . '</strong>'));
                }
            }

            if ($this->{$name}) {

                if (!\sJo\Data\Validate::is($attr['type'], $this->{$name}, $attr['length'], $attr['values'])) {

                    $this->setError($name, I18n::__(
                        'The field %s must be %s type and have maximum size %s.',
                        '<strong>' . $label . '</strong>',
                        '<strong>' . $attr['type'] . '</strong>',
                        '<strong>' . $attr['length'] . '</strong>'
                    ));
                }
            }
        }

        return !$this->hasErrors();
    }
}
