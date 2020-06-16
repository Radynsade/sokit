<?php

namespace views\Login;

use core\Page;
use modules\Auth\Auth;

final class Login extends Page {
    public $errorMessage;

    public function __construct() {
        $this->beforeLoad();
        $this->title = 'Вход';
        $this->description = 'Страница авторизации';
        $this->keywords = 'вход, страница, авторизация, авторизации, логин';
        $this->onFormSubmit();
        $this->setContent('LoginForm.phtml');
    }

    private function onFormSubmit() : void {
        if (!empty($_POST['completeLogin'])) {
            if (!Auth::enter($_POST['username'], $_POST['password'], '/')) {
                $this->errorMessage = Auth::$error;
                return;
            };
        }
    }

    private function beforeLoad() : void {
        if (!empty($_SESSION['user'])) {
            header('Location: /');
            die();
        };
    }
}
