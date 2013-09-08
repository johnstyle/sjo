<?php

namespace PHPTools\Helpers;

function Autoload($path)
{
	spl_autoload_register(
	    function ($className) use ($path) {
	        $className = str_replace('\\', '/', $className);
            $filename = $path . '/' . $className . '.php';
            if(file_exists($filename)) {
	           require $filename;
            }
	    }
	);
}
