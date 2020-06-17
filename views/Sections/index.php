<?php

namespace views\Sections;

use core\tools\Tools;
use core\Page;
use modules\Auth\Auth;

final class Sections extends Page {
    public function __construct() {
        $this->beforeLoad();
        $this->title = 'Разделы';
        $this->description = 'Страница разделов пользователя';
        $this->keywords = 'страница, разделы, разделов, пользователь, профиль, пользователя';
        $this->onFormSubmit();
        $this->setContent('Sections.phtml');
    }

    private function beforeLoad() : void {
        if (empty($_SESSION['user'])) {
            header('Location: /login');
            die();
        };
    }

    private function onFormSubmit() : void {
        Tools::onSubmit('exit', function() {
            Auth::exit('/');
        });
    }
}
