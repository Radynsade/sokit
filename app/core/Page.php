<?php

namespace core;

use core\tools\Tools;

abstract class Page {
    public $title;
    public $description;
    public $keywords;
    public $author;
    public $content;
    protected $cacheFile;
    private $cacheTime = 18000;

    abstract public function __construct();

    protected function setContent(string $fileName, bool $caching = false) : void {
        if (!$caching) {
            $this->writeContent($fileName);
            return;
        }

        $this->setCacheFileName($fileName);

        if ($this->isCached()) {
            $this->content = readFile($this->cacheFile);
            return;
        }

        $this->writeAndCache($fileName);
    }

    private function writeContent(string $fileName) : void {
        ob_start();
        include_once './views/' . $this->getViewName() . '/' . $fileName;
        $this->content = ob_get_contents();
        ob_clean();
    }

    private function writeAndCache(string $fileName) : void {
        ob_start();

        $cached = fopen('./cached/' . $this->cacheFile, 'w');
        include_once './views/' . $this->getViewName() . '/' . $fileName;
        $this->content = ob_get_contents();
        fwrite($cached, ob_get_contents());
        fclose($cached);

        ob_clean();
    }

    private function setCacheFileName(string $fileName) : void {
        $realPath = Tools::getRealPath($_SERVER['REQUEST_URI']);
        $namePath = !empty($realPath) ? '-' . str_replace('/', '-', $realPath) : '';
        $this->cacheFile = $this->getViewName() . '-' . substr_replace($fileName, "", -6) . $namePath . '.html';
    }

    private function isCached() : bool {
        return (file_exists($this->cacheFile) && time() - $this->cacheTime < filemtime($this->cacheFile));
    }

    protected function getViewName() : string {
        return (new \ReflectionClass($this))->getShortName();
    }
}
