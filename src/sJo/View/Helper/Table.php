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

        if ($element['thead'] === null
            && is_array($element['tbody'])) {
            $thead = array();
            foreach($element['tbody'] as $line) {
                foreach($line as $name=>$value) {
                    $name = ucfirst($name);
                    if (!in_array($name, $thead)) {
                        $thead[] = $name;
                    }
                }
            }
            $element['thead'] = $thead;
            unset($thead);
        }

        if (is_array($element['thead'])) {
            foreach($element['thead'] as &$thead) {
                if (!is_array($thead)) {
                    $thead = array(
                        'value' => $thead
                    );
                }

                $thead = Lib\Arr::extend(array(
                    'align' => null
                ), $thead);
            }
        }

        if ($element['actions']) {
            if (is_array($element['thead'])) {
                $element['thead'][] = array(
                    'value' => Lib\I18n::__('Actions'),
                    'align' => 'right'
                );
            }
            if (is_array($element['tbody'])) {
                foreach($element['tbody'] as &$line) {
                    $line->actions = '';
                    foreach($element['actions'] as $action=>$options) {
                        if(!is_array($options)) {
                            $action = $options;
                            $options = array(
                                'href' => Router::link(null, array('method' => $action, $instance->getPrimaryKey() => $line->{$instance->getPrimaryKey()}))
                            );
                        }

                        switch($action) {
                            case 'delete':
                                $icon = 'trash';
                            break;
                            case 'update':
                                $icon = 'edit';
                                break;
                            case 'create':
                                $icon = 'plus';
                                break;
                            default:
                                $icon = $action;
                                break;
                        }

                        $line->actions .= Link::create(array_merge(array(
                            'icon' => $icon
                        ), $options))->html();
                    }
                }
            }
        }

        return $element;
    }
}
