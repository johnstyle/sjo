<?php

namespace sJo\Model\Control;

use sJo\Libraries\I18n;

trait Validate
{
    /**
     * Validate fields values
     *
     * @return bool
     */
    protected function validate ()
    {
        foreach ($this->getTableColumns() as $name => $attr) {

            // Get field label
            $label = $this->getFieldLabel($name);

            if (is_null($this->{$name})
                || '' === $this->{$name}) {

                // Check required
                if(true === $attr['required']) {

                    if(null !== $attr['default']) {

                        $this->{$name} = $attr['default'];

                    } else {

                        $this->setError($name, I18n::__('The field %s is required.', '<strong>' . $label . '</strong>'));
                    }
                }
            }

            if (!is_null($this->{$name})
                && '' !== $this->{$name}) {

                // Check length
                if (!\sJo\Data\Validate::is($attr['type'], $this->{$name}, $attr['length'], $attr['values'])) {

                    $this->setError($name, I18n::__(
                        'The field %s must be %s type and have maximum size %s.',
                        '<strong>' . $label . '</strong>',
                        '<strong>' . $attr['type'] . '</strong>',
                        '<strong>' . $attr['length'] . '</strong>'
                    ));
                }

                // Check unique
                if(true === $attr['unique']) {

                    $exists = $this->db()->value($this->getPrimaryKey(), array(
                        $this->getPrimaryKey() => array(
                            'value' => $this->getPrimaryValue(),
                            'operator' => '!='
                        ),
                        $name => $this->{$name}
                    ));

                    if ($exists) {

                        $this->setError($name, I18n::__(
                            'This %s already exists.',
                            '<strong>' . $label . '</strong>'
                        ));
                    }
                }
            }
        }

        return !$this->hasErrors();
    }
}
