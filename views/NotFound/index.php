<?php

namespace views\NotFound;

use core\Page;

class NotFound extends Page {
    public function __construct() {
        $this->title = 'Страница не найдена';
        $this->description = 'Такой страницы не существует';
        $this->keywords = 'страница, не, найдена, существует, такой, страницы';
        $this->setContent('NotFound.phtml');
    }
}
