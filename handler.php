<?php

require_once './predefined.php';
require_once "./views/{$_POST['viewName']}/{$_POST['actionName']}.php";

session_start();

$class = 'views\\' . $_POST['viewName'] . '\\' . $_POST['actionName'];
$page = new $class;
