<?php

require_once 'autoloader.php';

use core\tools\Tools;
use core\tools\Data;
use core\Installer;

$config = Tools::readJSON('config.json');
$connect = new Data(
    $config['database']['host'],
    $config['database']['user'],
    $config['database']['password'],
    $config['database']['name']
);

$command = $argv[1];
$arguments = array_slice($argv, 2);

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
