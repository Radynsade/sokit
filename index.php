<?php

require './app/tools/Data.php';
require './app/core/Installer.php';
require './app/core/Router.php';
require './app/core/Page.php';
require './app/core/Builder.php';

use core\Installer;
use core\Router;
use core\Builder;

// Check if website is deployed and deploy it if not
Installer::init();

// Read config file
$config = json_decode(file_get_contents('config.json'), true);

// Create router
$router = new Router($config['router']);
$router->readPath($_SERVER['REQUEST_URI']);

session_start();

if (empty($_SESSION['id'])) {
    Builder::render('Login');
}
