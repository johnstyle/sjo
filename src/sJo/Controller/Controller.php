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
    use Action;
    use Validate;
    use Format;
    use Event;

    /** @var string $className Controller class name */
    public $className;
    /** @var string $name */
    public $name;
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
        $this->className = get_called_class();
    }
}
