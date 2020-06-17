<?php

function getClassName($class) {
    $explodedPath = explode('\\', $class);
    return end($explodedPath);
}

// Load core components and libraries
spl_autoload_register(function($class) {
    $className = getClassName($class);

    $directories = [
        './app/core/interfaces/',
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

// Load modules
foreach (new DirectoryIterator('./app/modules/') as $file) {
    if ($file->isDot()) continue;

    if ($file->isDir()) {
        require_once "./app/modules/{$file}/include.php";
    }
}
