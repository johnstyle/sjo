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

namespace PHPTools\Libraries;

abstract class Event
{
    /**
     * Actions enregistrés
     *
     * @var private
     */
    private static $registered = array();

    /**
     * Execution des événements
     *
     * @param string $name Nom de l'événement
     * @param array $args Arguments de l'événement
     * @return void
     */
    public static function __callStatic($name, $args)
    {
        if (self::has($name)) {
            ksort(self::$registered[$name]);
            foreach (self::$registered[$name] as $events) {
                foreach ($events as $event) {
                    call_user_func_array($event, $args);
                }
            }
        }
    }

    /**
     * Enregistrement des actions
     *
     * @param string $name Nom de l'événement
     * @param closure $callback Action à executer
     * @param int $priority Priorité de l'action
     * @return void
     */
    public static function register($name, $callback, $priority = 10)
    {
        if (!is_callable($callback)) {
            Exception::error(I18n::__('The second parameter must be a function'));
        }
        self::$registered[$name][$priority][] = $callback;
    }

    /**
     * Vérifie si un événement contient des actions
     *
     * @param string $name Nom de l'événement
     * @return boolean
     */
    public static function has($name)
    {
        if (isset(self::$registered[$name])) {
            return true;
        } else {
            return false;
        }
    }
}
