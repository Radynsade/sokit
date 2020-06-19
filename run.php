<?php

require_once './predefined.php';

use core\Installer;

$command = $argv[1]; // Get command
$arguments = array_slice($argv, 2); // Get arguments

if ($command === 'deploy') {
    if (!empty($arguments)) {
        Installer::deployModules($arguments);
        return;
    }

    Installer::deployModules();
}

if ($command === 'remove') {
    if (!empty($arguments)) {
        Installer::removeModules($arguments);
        return;
    }

    Installer::removeModules();
}

if ($command === 'install') {
    Installer::install();
}

if ($command === 'redeploy') {
    if (!empty($arguments)) {
        Installer::redeployModules($arguments);
        return;
    }

    Installer::redeployModules();
}
