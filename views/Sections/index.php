<?php

namespace views\Sections;

use core\Page;

final class Sections extends Page {
    public function __construct() {
        $this->title = 'Разделы';
        $this->description = 'Страница разделов пользователя';
        $this->keywords = 'страница, разделы, разделов, пользователь, профиль, пользователя';
        $this->setContent('Sections.phtml');
    }
}
