<?php

namespace core\tools;

class Tools {
    public static function readJSON(string $file) : array {
        return json_decode(file_get_contents($file), true);
    }

    public static function onSubmit(string $submitName, callable $callback) : void {
        if (!empty($_POST[$submitName])) {
            $callback();
        }
    }

    public static function redirect(string $path) : void {
        header("Location: {$path}");
        die();
    }

    public static function getNow() {
        return date('Y-m-d H:i:s');
    }
}
