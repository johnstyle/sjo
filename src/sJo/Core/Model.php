<?php

/**
 * Gestion des Models
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
 * Gestion des Models
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
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
     * @return \sJo\Core\Model
     */
    public function __construct(&$instance)
    {
        $this->Controller =& $instance;
        $this->Core =& $instance->Core;
        $this->Logger =& $instance->Logger;
    }
}
