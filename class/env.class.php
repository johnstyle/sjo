<?php

    /**
     * Gestion des variables d'environement
     */
    abstract class env{

        /**
         * Gestion des différents tableaux de variables d'environement
         * 
         * @method string get(string $attr, string $default)
         * @method bool getExists(string $attr)
         * @method void getSet(string $attr, string $value)
         * 
         * @method string post(string $attr, string $default)
         * @method bool postExists(string $attr)
         * @method void postSet(string $attr, string $value)
         * 
         * @method string files(string $attr, string $default)
         * @method bool filesExists(string $attr)
         * @method void filesSet(string $attr, string $value)
         * 
         * @method string request(string $attr, string $default)
         * @method bool requestExists(string $attr)
         * @method void requestSet(string $attr, string $value) 
         * 
         * @method string session(string $attr, string $default)
         * @method bool sessionExists(string $attr)
         * @method void sessionSet(string $attr, string $value)
         * 
         * @method string server(string $attr, string $default)
         * @method bool serverExists(string $attr)
         * @method void serverSet(string $attr, string $value)
         * 
         * @method string cookie(string $attr, string $default)
         * @method bool cookieExists(string $attr)
         * @method void cookieSet(string $attr, string $value)
         * 
         * @method string env(string $attr, string $default)
         * @method bool envExists(string $attr)
         * @method void envSet(string $attr, string $value)
         */
        public static function __callStatic($method, $args=false){
            if(preg_match("#^([a-z]+?)(Set|Exists)?$#i", $method, $match)){
                $var    = '_'.strtoupper($match[1]);
                $action = isset($match[2]) ? $match[2] : false;
                $attr   = self::_(0, false, $args);
                $value  = self::_(1, false, $args);
                global $$var;
                if(isset($$var)){
                    switch($action){
                        default:
                            return self::_($attr, $value, $$var);
                        break;
                        case 'Exists':
                            return self::_e($attr, $$var);
                        break;
                        case 'Set':
                            self::_s($attr, $value, $$var);
                        break;
                    }
                }
            }
        }
        
        /**
         * Récupération d'une valeur
         * 
         * @param string $attr Clé du tableau
         * @param string $default Valeurs par défaut
         * @param string $var Tableau de données
         */
        public static function _($attr, $default, &$var){
            return isset($var[$attr]) && !empty($var[$attr]) ? str::fromEnv($var[$attr]) : $default;
        }
        
        /**
         * Vérification de l'existance d'une valeur
         *
         * @param string $attr Clé du tableau
         * @param string $var Tableau de données
         */        
        public static function _e($attr, &$var){
            return isset($var[$attr]) && !empty($var[$attr]) ? true : false;
        }
        
        /**
         * Définition d'une valeur
         *
         * @param string $attr Clé du tableau
         * @param string $default Valeur
         * @param string $var Tableau de données
         */        
        public static function _s($attr, $value, &$var){
            $var[$attr] = $value;
        }
    }