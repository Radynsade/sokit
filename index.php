<?php

require './autoloader.php';

use core\tools\Data;
use core\Installer;
use core\Router;
use core\Builder;
use libraries\Crypter;

session_start();

// Read config file
$config = json_decode(file_get_contents('config.json'), true);

// Check if website is deployed and deploy it using configuration if not
Installer::init($config);

// Create global connection
$connect = new Data(
    $config['database']['host'],
    $config['database']['user'],
    $config['database']['password'],
    $config['database']['name'],
);

// Create encrypter for user login
$loginCrypter = new Crypter('AES-128-CBC','8259561259121120','Sokit2Key');

// Create router
$router = new Router($config['router']);
$router->readPath($_SERVER['REQUEST_URI']);

// Render page
Builder::render($router->result['view'], $config['main']['theme']);
