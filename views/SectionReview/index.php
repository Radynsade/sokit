<?php

namespace views\SectionReview;

use core\Page;
use modules\Sections\Section;

final class SectionReview extends Page {
    public $section;

    public function __construct() {
        $this->title = 'Раздел ###';
        $this->description = 'Страница раздела ###';
        $this->keywords = 'разделы, страница, создать, секции';
        $this->section = Section::get($GLOBALS['url']['id']);
        $this->setContent('Section.phtml', true);
    }
}
