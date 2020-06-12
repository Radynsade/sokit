<?php

namespace core;

class Page {
    public $metaData;
    public $content;

    protected function setContent(string $fileName) {
        ob_start();
        include_once './views/' . (new \ReflectionClass($this))->getShortName() . '/' . $fileName;
        $content = ob_get_contents();
        ob_clean();

        return $content;
    }
}
