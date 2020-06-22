<?php

require_once './predefined.php';

use core\Router;
use core\Builder;
use core\Post;

session_start();

Post::recieve();

// Create router
$router = new Router($config['router']);
$router->readPath($_SERVER['REQUEST_URI']);
$GLOBALS['url'] = $router->result['url'] ?? [];
$GLOBALS['view'] = $router->result['view'] ?? '';

// Render page
Builder::render($router->result['view'], $config['main']['theme']);
