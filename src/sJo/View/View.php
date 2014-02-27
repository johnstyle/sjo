<?php

/**
 * Gestion des Vues
 *
 * PHP version 5
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */

namespace sJo\View;

use sJo\Controller\Controller;
use sJo\Object\Closure;
use sJo\Loader\Router;

/**
 * Gestion des Vues
 *
 * @package  sJo
 * @category Core
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */
final class View extends Closure
{
    /** @var Controller Controller references */
    public $controller;

    /**
     * Constructor
     *
     * @param Controller $controller Controller references
     * @return \sJo\View\View
     */
    public function __construct(Controller &$controller)
    {
        $this->controller = $controller;
    }

    /**
     * Inclusion du header
     *
     * @return void
     */
    public function header()
    {
        $this->inc('header');
    }

    /**
     * Inclusion du footer
     *
     * @return void
     */
    public function footer()
    {
        $this->inc('footer');
    }

    public function method()
    {
        $this->inc(Router::$controller . '/' . Router::$method);
    }

    /**
     * Affichage de la vue courante
     *
     * @return void
     */
    public function display()
    {
        if (file_exists(Router::$viewFile)) {
            $this->inc(Router::$viewFile);
        } else {
            $this->header();
            $this->method();
            $this->footer();
        }
    }

    /**
     * Inclusion d'une vue
     *
     * @param string $filename Nom du fichier
     * @param array $vars Variables spécifiques à la vue
     * @return void
     */
    public function inc($filename, array $vars = null)
    {
        if (!strstr($filename, '.php')) {
            $filename = Router::$viewRoot . '/' . $filename . '.php';
        }

        parent::inc($filename, $vars);
    }
}
