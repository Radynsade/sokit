<?php

function getClassName($class) {
    $explodedPath = explode('\\', $class);
    return end($explodedPath);
}

spl_autoload_register(function($class) {
    $className = getClassName($class);

    $directories = [
        './app/core/tools/',
        './app/core/',
        './app/libraries/'
    ];

    foreach ($directories as $directory) {
        if (file_exists($directory . $className . '.php')) {
            require_once $directory . $className . '.php';
            return;
        }
    }
});
