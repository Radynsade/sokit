<?php

$config = json_decode(file_get_contents('config.json'), true);

require './app/tools/Data.php';
require './app/core/Installer.php'; core\Installer::init();
require './app/core/Page.php';
require './app/core/Builder.php';

session_start();

if (empty($_SESSION['id'])) {
    core\Builder::build('Login');
}
