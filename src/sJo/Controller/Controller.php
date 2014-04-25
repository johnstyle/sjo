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

/**
 * Gestion des Controlleurs
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */
class Controller
{
    use Event;

    /** @var string $controllerClass Controller class name */
    public $controllerClass;
    /** @var string $controller */
    public $controllerName;
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
    public function __construct()
    {
        $this->component = new Component\Component($this);
        $this->controllerClass = get_called_class();
        $reflectionClass = new \ReflectionClass($this->controllerClass);
        $this->controllerName = $reflectionClass->getShortName();
        $this->name = strtolower($this->controllerName);
    }
}
