<?php

$config = json_decode(file_get_contents('config.json'), true);

session_start();

echo 'Hey!';
