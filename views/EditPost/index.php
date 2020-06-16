<?php

namespace views\EditPost;

use core\Page;
use modules\Auth\Auth;

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
        if (empty($_SESSION['user'])) {
            header('Location: /login');
            die();
        };
    }

    private function onFormSubmit() : void {
        if (!empty($_POST['addPost'])) {
            echo 1;
            header('Location: /edit');
            die();
        }

        if (!empty($_POST['exit'])) {
            Auth::exit('/');
        }
    }
}
