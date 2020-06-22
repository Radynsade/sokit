<?php

namespace views\EditPost;

use modules\Sections\Section;
use core\tools\Tools;

class Edit {
    public function __construct() {
        $section = Section::get($_POST['sectionId']);
        $section->update($_POST['title'], $_POST['description']);
        Tools::redirect('/');
    }
}
