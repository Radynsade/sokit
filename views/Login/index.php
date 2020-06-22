<?php

namespace views\Login;

use core\Page;

final class Login extends Page {
    public $errorMessage;

    public function __construct() {
        $this->beforeLoad();
        $this->title = 'Вход';
        $this->description = 'Страница авторизации';
        $this->keywords = 'вход, страница, авторизация, авторизации, логин';
        if (!empty($_POST['AuthError'])) $this->errorMessage = $_POST['AuthError'];
        $this->setContent('LoginForm.phtml');
    }

    private function beforeLoad() : void {
        if (!empty($_SESSION['user'])) {
            header('Location: /');
            die();
        };
    }
}
