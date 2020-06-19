<?php

require_once './predefined.php';

use core\Router;
use core\Builder;
use core\tools\SQLB;

session_start();

// Create router
$router = new Router($config['router']);
$router->readPath($_SERVER['REQUEST_URI']);
$GLOBALS['url'] = $router->result['url'] ?? [];
$GLOBALS['view'] = $router->result['view'] ?? '';

$query = SQLB::write('users')
    ->data(['username', 'email', 'password'])
    ->get()
    ->insert()
    ->sql;

var_dump($query);

// Render page
Builder::render($router->result['view'], $config['main']['theme']);
