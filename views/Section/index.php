<?php

namespace views\Section;

use core\Page;

class Section extends Page {
    public function __construct() {
        $this->title = 'Раздел ###';
        $this->description = 'Страница раздела ###';
        $this->keywords = 'разделы, страница, создать, секции';
        $this->setContent('Section.phtml');
    }
}
