<?php

require_once './autoloader.php';

use core\tools\Data;
use core\tools\Tools;
use core\Router;
use core\Builder;

session_start();

// Read config file
$config = Tools::readJSON('config.json');

// Create global connection
$connect = new Data(
    $config['database']['host'],
    $config['database']['user'],
    $config['database']['password'],
    $config['database']['name'],
);

// Create router
$router = new Router($config['router']);
$router->readPath($_SERVER['REQUEST_URI']);
$GLOBALS['url'] = $router->result['url'] ?? [];
$GLOBALS['view'] = $router->result['view'] ?? '';

// Render page
Builder::render($router->result['view'], $config['main']['theme']);
