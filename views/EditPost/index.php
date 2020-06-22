<?php

namespace views\EditPost;

use core\tools\Tools;
use core\Page;
use modules\Auth\Auth;
use modules\Sections\Section;

final class EditPost extends Page {
    public $section;

    public function __construct() {
        $this->beforeLoad();
        $this->title = 'Разделы';
        $this->description = 'Страница разделов пользователя';
        $this->keywords = 'страница, разделы, разделов, пользователь, профиль, пользователя';

        if (empty($GLOBALS['url']['id'])) {
            $this->setContent('AddForm.phtml');
        } else {
            $this->section = Section::get($GLOBALS['url']['id']);
            $this->setContent('EditForm.phtml');
        }
    }

    private function beforeLoad() : void {
        if (!Auth::isAuthorized()) {
            Tools::redirect('/login');
        };
    }
}
