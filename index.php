<?php

require_once './predefined.php';

use core\Router;
use core\Builder;
use core\tools\Query;
use core\tools\Tools;

session_start();

// Create router
$router = new Router($config['router']);
$router->readPath($_SERVER['REQUEST_URI']);
$GLOBALS['url'] = $router->result['url'] ?? [];
$GLOBALS['view'] = $router->result['view'] ?? '';

$query = Query::write('users')
    ->where([
        'id' => 1
    ])
    ->data(['username', 'email', 'password'])
    ->orderBy('id')
    ->get()
    ->sql;

$user = $connect->send($query);
var_dump($user);

// Render page
Builder::render($router->result['view'], $config['main']['theme']);
