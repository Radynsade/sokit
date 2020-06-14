<?php

namespace core;

class Router {
    public $routes;
    public $notFound;
    public $result = [];

    public function __construct(array $routerConfig) {
        $this->routes = $routerConfig['routes'];
        $this->notFound = $routerConfig['404'];
    }

    public function readPath(string $path) : void {
        $pathElements = Router::splitPath($path);
        $this->result['view'] = $this->notFound;

        foreach ($this->routes as $view => $link) {
            $linkElements = array_filter(explode('/', substr($link, 1)));

            if ($this->isMatches($pathElements, $linkElements)) {
                $this->result['view'] = $view;
                break;
            }
        }
    }

    private function compare(string $pathElement, string $linkElement) : int {
        if ($pathElement === $linkElement) {
            return 0;
        } else if ($linkElement[0] === ':') {
            $this->result['url'][substr($linkElement, 1)] = $pathElement;
            return 0;
        }

        return -1;
    }

    private function isMatches(array $pathElements, array $linkElements) : bool {
        if (count($pathElements) === count($linkElements)) {
            if (count(array_uintersect($pathElements, $linkElements, [$this, 'compare'])) === count($pathElements)) {
                return true;
            } else {
                $this->result['url'] = [];
            }
        }

        return false;
    }

    private static function splitPath(string $path) : array {
        return substr($path, -1) === '/'
            ? array_filter(explode('/', substr(substr($path, 1), 0, -1)))
            : array_filter(explode('/', substr($path, 1)));
    }
}
