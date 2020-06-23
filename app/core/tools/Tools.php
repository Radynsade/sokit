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

    public static function getNow() : string {
        return date('Y-m-d H:i:s');
    }

    public static function isAssoc(array $array) : bool {
        if (array() === $array) return false;
        return array_keys($array) !== range(0, count($array) - 1);
    }

    public static function getRealPath(string $path) : string {
        return substr($path, -1) === '/'
            ? substr(substr($path, 1), 0, -1)
            : substr($path, 1);
    }
}
