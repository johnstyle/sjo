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

namespace sJo\Core;

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
   /**
    * Core references
    *
    * @var \stdClass
    */
    public $Core;

    /**
     * Logger references
     *
     * @var Logger
     */
    public $Logger;

    /**
     * Module references
     *
     * @var Module
     */
    public $Module;

    /**
     * Constructeur
     *
     * @return \sJo\Core\Controller
     */
    final public function __construct ()
    {
        $this->Core = new \stdClass();
        /** @var Session */
        $this->Core->Session = new Session($this);
        /** @var Request */
        $this->Core->Request = new Request($this);
        /** @var Alert */
        $this->Core->Alert = new Alert($this);
        /** @var Logger */
        $this->Logger = new Logger($this);
    }

    public function __viewPreload ()
    {
    }

    public function __viewLoaded ()
    {
    }   

    public function __viewCompleted ()
    {
    }
}
