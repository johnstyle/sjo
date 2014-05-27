<?php

/**
 * Base de données Drivers
 *
 * PHP version 5
 *
 * @package  sJo
 * @category Db
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */

namespace sJo\Db\Elastica;

use Elastica\Client;

/**
 * Base de données Drivers
 * Base de données Drivers
 *
 * @package  sJo
 * @category Db
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */
class Elastica
{
    private $resource;

    public function __construct (array $config = array(), $callback = null)
    {
        $this->resource = new Client($config, $callback);
    }

    public function getIndex ($name)
    {
        return $this->resource->getIndex($name);
    }
}
