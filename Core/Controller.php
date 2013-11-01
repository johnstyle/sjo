<?php

/**
 * Gestion des Controlleurs
 *
 * PHP version 5
 *
 * @package  PHPTools
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/PHPTools.git
 */

namespace PHPTools;

/**
 * Gestion des Controlleurs
 *
 * @package  PHPTools
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/PHPTools.git
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
     * Constructeur
     *
     * @return \PHPTools\Controller
     */
    final public function __construct ()
    {
        $this->Core = new \stdClass();
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
