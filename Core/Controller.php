<?php
/**
 * PHPTools
 *
 * PHP version 5
 *
 * @package  PHPTools
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/PHPTools.git
 */

namespace PHPTools;

abstract class Controller
{
   /**
    * Core references
    *
    * @var object
    */
    public $Core;

   /**
    * Libraries references
    *
    * @var object
    */
    public $Libraries;    

   /**
    * Model references
    *
    * @var object
    */
    public $Model;

   /**
    * Constructor
    *
    * @return void
    */
    final public function __construct ()
    {
        $this->Core = new \stdClass();
        $this->Libraries = new \stdClass();
    }

   /**
    * Control the access restrictions
    *
    * @return void
    */
    public function __restrictedAccess ()
    {
        $this->Core->Session->check();
    }

   /**
    * Loader executed when loading the class
    *
    * @return void
    */
    public function __load ()
    {
    }    
}
