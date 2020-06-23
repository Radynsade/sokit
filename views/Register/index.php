<?php

namespace views\Register;

use core\Page;
use modules\Auth\Auth;

final class Register extends Page {
    public $errorMessage;

    public function __construct() {
        $this->title = 'Регистрация';
        $this->description = 'Страница регистрации';
        $this->keywords = 'вход, страница, регистрация, регистрации, новый, аккаунт, профиль';
        if (!empty($_POST['AuthError'])) $this->errorMessage = $_POST['AuthError'];
        $this->setContent('RegForm.phtml');
    }

    private function onFormSubmit() : void {
        if (!empty($_POST['completeReg'])) {
            if (!Auth::createUser($_POST['username'], $_POST['newPassword'], $_POST['repeatPassword'])) {
                $this->errorMessage = Auth::$error;
                return;
            };

            Auth::enter($_POST['username'], $_POST['newPassword'], '/');
        }
    }
}
