<?php

namespace core;

final class Post {
    public static function send(string $key, $value) : void {
        $_SESSION['post'][$key] = $value;
    }

    public static function recieve() : void {
        if (!empty($_SESSION['post'])) $_POST = $_SESSION['post'];
        unset($_SESSION['post']);
    }
}
