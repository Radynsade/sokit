<?php

namespace views\EditPost;

use modules\Sections\Section;
use core\tools\Tools;
use core\Page;

class Edit {
    public function __construct() {
        $section = Section::get($_POST['sectionId']);
        $section->update($_POST['title'], $_POST['description']);
        Page::cleanCache(
            'SectionReview',
            'Section.phtml',
            '/section' . '/' . $_POST['sectionId']
        );
        Tools::redirect('/');
    }
}
