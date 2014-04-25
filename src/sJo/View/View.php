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
        return $this->inc(dirname(Router::$viewFile) . '/' . basename(Router::$viewFile, '.php') . '/' . Router::$method . '.php');
    }

    /**
     * Affichage de la vue courante
     *
     * @param $render
     *
     * @return void
     */
    public function display($render)
    {
        if (file_exists(Router::$viewFile)) {

            $this->inc(Router::$viewFile);

        } else {

            $this->header();

            if(!$this->method()
                && !is_null($render)
                && method_exists($render, 'render')) {

                $render->render();
            }

            $this->footer();
        }
    }

    /**
     * Inclusion d'une vue
     *
     * @param string $filename Nom du fichier
     * @param array $vars Variables spécifiques à la vue
     * @return bool
     */
    public function inc($filename, array $vars = null)
    {
        if (!strstr($filename, '.php')) {

            $filename = Router::$viewRoot . '/' . $filename . '.php';
        }

        return parent::inc($filename, $vars);
    }
}
