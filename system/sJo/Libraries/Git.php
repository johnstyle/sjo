<?php
/**
 * sJo
 *
 * PHP version 5
 *
 * @package  sJo
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/sjo.git
 */

namespace sJo\Libraries;

abstract class Git
{
    private static $HEAD;

    /**
     * Récupère la branche active du projet courant
     *
     * @param bool|string $module Nom du sous-module, par défaut le projet principal
     * est sélectionné
     * @return string Nom de la branche
     */
    public static function branch ($module = false)
    {
        if (!isset(self::$HEAD[$module])) {
            $file = ROOT . '/.git/' . ($module ? 'modules/' . $module . '/' : '') . 'HEAD';

            /** Ancienne version de Git */
            if (!file_exists($file)) {
                $file = ROOT . '/' . ($module ? $module . '/' : '') . '.git/HEAD';
            }

            $HEAD = file_get_contents($file);
            if (preg_match("#refs/heads/(.+)$#", $HEAD, $match)) {
                self::$HEAD[$module] = trim($match[1]);
            } else {
                self::$HEAD[$module] = 'no branch';
            }
        }
        return self::$HEAD[$module];
    }
}
