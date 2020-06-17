<?php

require_once 'autoloader.php';

use core\tools\Tools;
use core\Installer;

$config = Tools::readJSON('config.json');

$command = $argv[1];

if ($command === 'deploy') {
    Installer::init($config);
}
