<?php

require_once 'autoloader.php';

use core\tools\Tools;
use core\Installer;

$config = Tools::readJSON('config.json');

$command = $argv[1];
$arguments = array_slice($argv, 2);

if ($command === 'deploy') {
    if (!empty($arguments)) {
        Installer::deployModules($arguments);
        return;
    }

    Installer::init();
}

if ($command === 'remove') {
    if (!empty($arguments)) {
        Installer::removeModules($arguments);
        return;
    }

    Installer::removeModules();
}
