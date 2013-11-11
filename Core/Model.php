<?php

/**
 * Gestion des Models
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
 * Gestion des Models
 *
 * @package  PHPTools
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/PHPTools.git
 */
abstract class Model
{
    /**
     * Model references
     *
     * @var Controller
     */
    protected $Controller;

    /**
     * Core references
     *
     * @var object
     */
    protected $Core;

    /**
     * Logger references
     *
     * @var Logger
     */
    protected $Logger;

    /**
     * Module references
     *
     * @var object
     */
    protected $Module;

    /**
     * Constructor
     *
     * @param $instance
     * @return \PHPTools\Model
     */
    public function __construct(&$instance)
    {
        $this->Controller =& $instance;
        $this->Core =& $instance->Core;
        $this->Logger =& $instance->Logger;
    }
}
