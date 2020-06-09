<?php

$config = json_decode(file_get_contents('config.json'), true);

require './app/tools/Data.php';
require './app/tools/File.php';
require './app/core/Installer.php';

core\Installer::init();

$var = 'LALALA';
echo "My var is {$var}";

session_start();
