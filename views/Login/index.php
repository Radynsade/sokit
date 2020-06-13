<?php

namespace views\Login;

use core\Page;

class Login extends Page {
    public function __construct() {
        $this->title = 'Вход';
        $this->description = 'Страница авторизации';
        $this->keywords = 'вход, страница, авторизация, авторизации, логин';
        $this->setContent('LoginForm.phtml');
    }
}
