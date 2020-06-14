<?php

namespace views\Register;

use core\Page;

class Register extends Page {
    public function __construct() {
        $this->onFormSubmit();
        $this->title = 'Регистрация';
        $this->description = 'Страница регистрации';
        $this->keywords = 'вход, страница, регистрация, регистрации, новый, аккаунт, профиль';
        $this->setContent('RegForm.phtml');
    }

    private function onFormSubmit() {
        if (!empty($_POST['completeReg'])) {
            global $connect;

            $connect->addTo('users', [
                $_POST['username'] => 'username',
                $_POST['password'] => 'password'
            ]);
        }
    }
}
