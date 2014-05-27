<?php

/**
 * Gestion des Controlleurs
 *
 * PHP version 5
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */

namespace sJo\Controller;

use sJo\Libraries\I18n;

/**
 * Gestion des Controlleurs
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */
abstract class Controller
{
    use Event;

    /** @var string $controllerClass Controller class name */
    public $controllerClass;
    /** @var string $controller */
    public $controllerName;
    /** @var string $modelClass */
    protected static $modelClass;
    /** @var string $name */
    public $name;
    /** @var string $title */
    public $title;
    /** @var Component\Component $component */
    public $component;

    /**
     * Constructeur
     *
     * @return \sJo\Controller\Controller
     */
    public function __construct ()
    {
        $this->component = new Component\Component($this);
        $this->controllerClass = get_called_class();
        $reflectionClass = new \ReflectionClass($this->controllerClass);
        $this->controllerName = $reflectionClass->getShortName();
        $this->name = strtolower($this->controllerName);
    }

    /**
     * @param null $type
     *
     * @return null
     * @throws ControllerException
     */
    protected static function model ($type = null)
    {
        if (!is_null(static::$modelClass)) {

            $class = preg_replace('#\\\\([^\\\\]+)$#', '\\\\' . $type . '\\\\$1', static::$modelClass) . ucfirst($type);

            if (!class_exists($class)) {

                $class = static::$modelClass;
            }

            if (class_exists($class)) {

                return $class::getInstance();
            }

            throw new ControllerException(I18n::__('Class %s does not exsist.', $class));
        }

        throw new ControllerException(I18n::__('Model Class does not declared.'));
    }
}
