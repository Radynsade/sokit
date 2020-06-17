<?php

namespace views\EditPost;

use core\tools\Tools;
use core\Page;
use modules\Auth\Auth;
use modules\Sections\Section;

final class EditPost extends Page {
    public function __construct() {
        $this->beforeLoad();
        $this->title = 'Разделы';
        $this->description = 'Страница разделов пользователя';
        $this->keywords = 'страница, разделы, разделов, пользователь, профиль, пользователя';
        $this->onFormSubmit();
        $this->setContent('EditForm.phtml');
    }

    private function beforeLoad() : void {
        if (!Auth::isAuthorized()) {
            Tools::redirect('/login');
        };
    }

    private function onFormSubmit() : void {
        Tools::onSubmit('add', function() {
            $section = new Section($_POST['title'], $_POST['description']);
            $section->upload();
            Tools::redirect('/');
        });
    }
}
