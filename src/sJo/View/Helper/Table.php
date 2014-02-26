<?php

namespace sJo\View\Helper;

use sJo\Loader\Router;
use sJo\View\Helper\Dom\Dom;
use sJo\Libraries as Lib;

class Table extends Dom
{
    public function setElement($element)
    {
        $element = Lib\Arr::extend(array(
            'id' => null,
            'class' => null,
            'thead' => null,
            'tfoot' => null,
            'tbody' => null,
            'actions' => null,
            'bulk' => null
        ), $element);

        $instance = null;

        if (is_object($element['tbody'])) {
            $instance = $element['tbody'];
            $element['tbody'] = $instance->db()->results();
        }

        if ($element['thead'] === null && is_array($element['tbody'])) {
            $thead = array();
            foreach($element['tbody'] as $line) {
                foreach($line as $name=>$value) {
                    if (!in_array($name, $thead)) {
                        $thead[] = $name;
                    }
                }
            }
            $element['thead'] = $thead;
        }

        if ($element['actions']) {
            if ($element['thead'] && is_array($element['thead'])) {
                array_push($element['thead'], Lib\I18n::__('Actions'));
            }
            if ($element['tbody'] && is_array($element['tbody'])) {
                foreach($element['tbody'] as &$line) {
                    $line->actions = '';
                    foreach($element['actions'] as $action=>$options) {
                        if(!is_array($options)) {
                            $action = $options;
                            $options = array(
                                'href' => Router::link(null, array('method' => 'edit', $instance->getPrimaryKey() => $line->{$instance->getPrimaryKey()}))
                            );
                        }
                        $line->actions .= Link::create(array_merge(array(
                            'icon' => $action
                        ), $options))->html();
                    }
                }
            }
        }

        return $element;
    }
}
