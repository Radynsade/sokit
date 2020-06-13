<?php

namespace core;

class Page {
    public $title;
    public $description;
    public $keywords;
    public $author;
    public $content;

    protected function setContent(string $fileName) : void {
        ob_start();
        include_once './views/' . (new \ReflectionClass($this))->getShortName() . '/' . $fileName;
        $this->content = ob_get_contents();
        ob_clean();
    }
}
