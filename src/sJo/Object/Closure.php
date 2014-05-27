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

use sJo\Data\Validate;
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
        if (Validate::isCallable($data)) {
            $this->methods[$name] = $data;
        } else {
            $this->properties[$name] = $data;
        }

        return $this;
    }

    /**
     * @param $method
     * @param $args
     *
     * @return mixed
     * @throws \sJo\Exception\Exception
     */
    function __call($method, $args)
    {
        if (isset($this->methods[$method])) {

            return call_user_func_array($this->methods[$method], $args);

        } else {

            throw new Exception(I18n::__('Unknow method %s', $method));
        }
    }

    /**
     * @param       $filename
     * @param array $properties
     *
     * @return bool
     */
    public function inc($filename, array $properties = null)
    {
        $success = false;

        if($properties) {

            foreach($properties as $name=>$value) {

                $this->{$name} = $value;
            }
        }

        if (file_exists($filename)) {

            $success = true;
            require $filename;
        }

        if($properties) {

            foreach($properties as $name=>$value) {

                unset($this->{$name});
            }
        }

        return $success;
    }
}
