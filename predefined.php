<?php

require_once './autoloader.php';

use core\tools\Data;
use core\tools\Tools;

// Read config file
$config = Tools::readJSON('config.json');

// Create global connection
$connect = new Data(
    $config['database']['host'],
    $config['database']['user'],
    $config['database']['password'],
    $config['database']['name'],
);
