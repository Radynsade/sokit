<?php

namespace core;

class Builder {
    public static function build(string $viewName) : void {
        include_once "./views/{$viewName}/index.php";
        $class = 'views\\' . $viewName . '\\' . $viewName;
        $page = new $class;
    }
}
