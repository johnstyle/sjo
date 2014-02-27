<?php
/**
 * sJo
 *
 * PHP version 5
 *
 * @package  sJo
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */

namespace sJo\Object;

use sJo\Exception\Exception;
use sJo\Libraries\I18n;

class Closure
{
    private $methods = array();
    private $properties = array();

    function __get($name)
    {
        if (isset($this->properties[$name])) {
            return $this->properties[$name];
        }
    }

    function __set($name, $data)
    {
        if (is_callable($data)) {
            $this->methods[$name] = $data;
        } else {
            $this->properties[$name] = $data;
        }

        return $this;
    }

    function __call($method, $args)
    {
        if (isset($this->methods[$method])) {
            return call_user_func_array($this->methods[$method], $args);
        } else {
            Exception::error(I18n::__('Unknow method %s', $method));
        }
    }

    public function inc($filename, array $properties = null)
    {
        if($properties) {
            foreach($properties as $name=>$value) {
                $this->{$name} = $value;
            }
        }

        if (file_exists($filename)) {
            require $filename;
        }

        if($properties) {
            foreach($properties as $name=>$value) {
                unset($this->{$name});
            }
        }
    }
}
