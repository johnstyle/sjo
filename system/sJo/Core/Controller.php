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
     * Model references
     *
     * @var Model
     */
    public $Model;

   /**
    * Core references
    *
    * @var object
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
     * @var object
     */
    public $Module;

    /**
     * Constructeur
     *
     * @return \sJo\Controller
     */
    final public function __construct ()
    {
        $this->Core = new \stdClass();
        $this->Module = new \stdClass();
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
