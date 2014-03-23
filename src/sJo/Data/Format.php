<?php

namespace sJo\Data;

abstract class Format
{
    /**
     * Création d'une chaine d'URL
     *
     * @param string $str Chaine à traiter
     * @param bool|string $allowedChars Caractères autorisés
     * @return string
     */
    public static function toGuid($str, $allowedChars = false)
    {
        $str = self::noAccent(strtolower($str));
        $str = preg_replace("/[\s\n\r\t]+/", "-", $str);
        $str = preg_replace("/_/", "-", $str);
        $str = preg_replace("/[^a-z0-9-" . $allowedChars . "]*/", "", $str);
        $str = preg_replace("/-+/", "-", $str);
        $str = preg_replace("/(\.[^\/]*|[^\/]*\.|\/)-+/", "$1", $str);
        $str = preg_replace("/^([^\/]*\.|\/)?-+/", "$1", $str);
        $str = preg_replace("/-+(\.[^\/]*|\/)?$/", "$1", $str);
        $str = preg_replace("/[\/]+/", "/", $str);
        return $str;
    }

    /**
     * Stripslashes limité aux quotes
     *
     * @param string $str Chaine à traiter
     * @return string
     */
    public static function stripslashes($str)
    {
        if (is_array($str)) {
            $str = array_map(__NAMESPACE__ . '\Format::stripslashes', $str);
        } elseif (is_object($str)) {
            $str = (object)array_map(__NAMESPACE__ . '\Format::stripslashes', (array) $str);
        } else {
            $str = str_replace('\"', '"', $str);
            $str = str_replace("\'", "'", $str);
        }
        return $str;
    }

    /**
     * Supprime les accents d'une chaine
     *
     * @param string $str Chaine à traiter
     * @return string
     */
    public static function noAccent($str)
    {
        $str = htmlentities(html_entity_decode($str, false, SJO_CHARSET), false, SJO_CHARSET);
        $str = preg_replace('#\&([A-za-z])(?:acute|cedil|circ|grave|ring|tilde|uml)\;#', '\1', $str);
        $str = preg_replace('#\&([A-za-z]{2})(?:lig)\;#', '\1', $str);
        $str = preg_replace('#\&[^;]+\;#', '', $str);
        return $str;
    }

    /**
     * Remplacement des espaces
     *
     * @param string $str Chaine à traiter
     * @return string
     */
    public static function noBlank($str)
    {
        return trim(preg_replace("#(\n|\r|\s|\t|&nbsp;)+#i", " ", $str));
    }

    /**
     * Remplacement des saut de lignes
     *
     * @param string $str Chaine à traiter
     * @return string
     */
    public static function noBreak($str)
    {
        return trim(preg_replace("#(\s|\n|<br[\s]*/?>)+#", "\n", $str));
    }

    /**
     * Formatage pour une expression régulière
     *
     * @param string $var Chaine à traiter
     * @param boolean $blank Remplace les espaces
     * @return string
     */
    public static function regexp($var, $blank = true)
    {
        $var = str_replace(
            array('-', '?', '*', '+','^', '$', '#', '.', '(', ')', '[', ']', '{', '}'),
            array('\-', '\?', '\*', '\+', '\^', '\\$', '\#', '\.', '\(', '\)', '\[', '\]', '\{', '\}'),
            $var
        );

        if ($blank) {
            $var = preg_replace("#[\s]+#s", '[\s]+', $var);
        }

        return $var;
    }

    public static function camelize($var)
    {
        return strtr(ucwords(strtr($var, array('_' => ' ', '.' => '_ ', '\\' => '_ '))), array(' ' => ''));
    }
}
