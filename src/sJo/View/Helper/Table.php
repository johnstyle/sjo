<?php

namespace sJo\View\Helper;

use sJo\Data\Validate;
use sJo\Loader\Router;
use sJo\View\Helper\Dom\Dom;
use sJo\Libraries as Lib;

class Table extends Dom
{
    const DEFAULT_WRAPPER = 'tbody';

    protected $wrappers = array(
        self::DEFAULT_WRAPPER,
        'thead',
        'tfoot'
    );

    protected static $attributes = array(
        'thead' => null,
        'tfoot' => null,
        'tbody' => null,
        'actions' => null,
        'bulk' => null
    );

    public function setElement($element)
    {
        $element['attributes']['class'] .= ' table';

        $head = array();
        $instance = null;

        if (is_array($element['tbody'])) {
            foreach ($element['tbody'] as &$tbody) {

                if (is_object($tbody)) {

                    $tbody = $tbody->db()->results();
                }
            }
        }

        if ($element['thead'] === null
            && is_array($element['tbody'])) {

            $thead = array();

            foreach($element['tbody'] as $line) {

                foreach($line as $name=>$value) {

                    if (!in_array($name, $thead)) {
                        $thead[] = $name;
                    }
                }
            }

            $element['thead'] = $thead;
            unset($thead);
        }

        if (is_array($element['thead'])) {

            $arrayThead = array();

            foreach($element['thead'] as $key=>&$thead) {

                if (!is_array($thead)) {
                    if (!Validate::isInt($key)) {
                        $thead = array(
                            'name' => $key,
                            'label' => $thead
                        );
                    } else{
                        $thead = array(
                            'name' => $thead
                        );
                    }
                }

                $thead = Lib\Arr::extend(array(
                    'name' => $key,
                    'callback' => null,
                    'align' => null,
                    'label' => ucfirst(isset($thead['name']) ? $thead['name'] : $key)
                ), $thead);

                $head[] = $thead['name'];
                $arrayThead[$thead['name']] = $thead;
            }

            $element['thead'] = $arrayThead;
        }

        if (is_array($element['tbody'])) {

            if ($element['actions']) {

                if (is_array($element['thead'])) {

                    $element['thead']['actions'] = array(
                        'name' => 'actions',
                        'label' => Lib\I18n::__('Actions'),
                        'align' => 'right'
                    );
                }

                foreach($element['tbody'] as &$line) {

                    $line->actions = '';

                    foreach($element['actions'] as $action=>$options) {

                        if(!is_array($options)) {
                            $action = $options;
                            $options = array();
                        }

                        $options = Lib\Arr::extend(array(
                            'interface' => Router::$interface,
                            'controller' => null,
                            'href' => null,
                            'icon' => null
                        ), $options);

                        if (!$options['href']) {

                            $options['href'] = Router::link(
                                $options['interface'],
                                $options['controller'],
                                array(
                                    'method' => $action,
                                    $instance->getPrimaryKey() => $line->{$instance->getPrimaryKey()}
                                )
                            );
                        }

                        if (!$options['icon']) {

                            switch($action) {

                                case 'delete':
                                    $options['icon'] = 'trash';
                                    break;

                                case 'update':
                                    $options['icon'] = 'edit';
                                    break;

                                case 'create':
                                    $options['icon'] = 'plus';
                                    break;

                                default:
                                    $options['icon'] = $action;
                                    break;
                            }
                        }

                        $line->actions .= Link::create(array_merge(array(
                            'icon' => $options['icon']
                        ), $options))->html();
                    }
                }
            }

            if (count($head)) {

                foreach($element['tbody'] as &$line) {

                    foreach($line as $name=>$value) {

                        if (!in_array($name, $head)) {
                            unset($line->{$name});
                            continue;
                        }

                        if (isset($element['thead'][$name]['callback'])) {
                            $line->{$name} = call_user_func($element['thead'][$name]['callback'], $line->{$name});
                        }
                    }
                }
            }
        }

        return $element;
    }
}
