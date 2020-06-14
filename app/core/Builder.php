<?php

namespace core;

class Builder {
    public static function render(
        string $viewName,
        string $themeName
    ) : void {
        include_once "./views/{$viewName}/index.php";
        $class = 'views\\' . $viewName . '\\' . $viewName;
        $page = new $class;

        include_once "./themes/{$themeName}/Template.phtml";
    }
}
