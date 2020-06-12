<?php

namespace views\Login;

use core\Page;

class Login extends Page {
    public function __construct() {
        $this->metaData = [
            'title' => 'Вход',
            'description' => 'Страница авторизации',
            'keywords' => 'вход, страница, авторизация, авторизации, логин'
        ];

        $this->content = $this->setContent('LoginForm.phtml');

        echo $this->content;
    }
}
