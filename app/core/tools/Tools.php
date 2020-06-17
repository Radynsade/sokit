<?php

namespace core\tools;

class Tools {
    public static function readJSON(string $file) : array {
        return json_decode(file_get_contents($file), true);
    }
}
