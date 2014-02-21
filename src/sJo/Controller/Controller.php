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

use sJo\Core;

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
    use Event;

    /**
     * Controller class name
     *
     * @var string
     */
    public $className;

    /**
     * Controller name
     *
     * @var string
     */
    public $name;

   /**
    * Core references
    *
    * @var \stdClass
    */
    public $Core;

    /**
     * Logger references
     *
     * @var Core\Logger
     */
    public $Logger;

    /**
     * Module references
     *
     * @var Core\Module
     */
    public $Module;

    /**
     * Constructeur
     *
     * @return \sJo\Controller\Controller
     */
    public function __construct ()
    {
        $this->Core = new \stdClass();
        /** @var Core\Session */
        $this->Core->Session = new Core\Session($this);
        /** @var Core\Request */
        $this->Core->Request = new Core\Request($this);
        /** @var Core\Alert */
        $this->Core->Alert = new Core\Alert($this);
        /** @var Core\Logger */
        $this->Logger = new Core\Logger($this);

        $this->className = get_called_class();
    }
}
