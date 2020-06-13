<?php

namespace core;

class Builder {
    public static function render(string $viewName) : void {
        global $config;

        include_once "./views/{$viewName}/index.php";
        $class = 'views\\' . $viewName . '\\' . $viewName;
        $page = new $class;

        include_once "./themes/{$config['main']['theme']}/Template.phtml";
    }
}
