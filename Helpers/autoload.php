<?php

namespace PHPTools\Helpers;

function autoload($path)
{
	spl_autoload_register(
	    function ($className) use ($path) {
	        $className = str_replace('\\', '/', $className);
	        require $path . '/' . $className . '.php';
	    }
	);
}
