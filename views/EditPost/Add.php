<?php

namespace views\EditPost;

use modules\Sections\Section;
use core\tools\Tools;

class Add {
    public function __construct() {
        $section = Section::create($_POST['title'], $_POST['description'], $_SESSION['user']);
        $section->upload();
        Tools::redirect('/');
    }
}
