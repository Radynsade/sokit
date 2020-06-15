<?php

require './app/tools/Data.php';
require './app/tools/Auth.php';
require './app/tools/Crypter.php';
require './app/core/Installer.php';
require './app/core/Router.php';
require './app/core/Page.php';
require './app/core/Builder.php';

use tools\Crypter;
use tools\Data;
use core\Installer;
use core\Router;
use core\Builder;

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

// Create encrypter
$crypter = new Crypter('aria-256-cfb8','1234567891011121','Sokit');
$encrypted = $crypter->encrypt('Lalalallaa aksdgajsglkatlas sldkfhj;ldfjh gkasldjgklasdjglkjasdg');
$decrypted = $crypter->decrypt($encrypted);
var_dump($encrypted);
var_dump($decrypted);

// Create router
$router = new Router($config['router']);
$router->readPath($_SERVER['REQUEST_URI']);

// Render page
Builder::render($router->result['view'], $config['main']['theme']);




// if (empty($_SESSION['id'])) {
    // Builder::render('Login');
// }
