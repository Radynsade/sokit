<?php

namespace views\Register;

use core\Page;

class Register extends Page {
    public $errorMessage;

    public function __construct() {
        $this->title = 'Регистрация';
        $this->description = 'Страница регистрации';
        $this->keywords = 'вход, страница, регистрация, регистрации, новый, аккаунт, профиль';
        $this->onFormSubmit();
        $this->setContent('RegForm.phtml');
    }

    private function onFormSubmit() : void {
        global $connect;

        var_dump($connect->getAllFrom('users'));

        if (!empty($_POST['completeReg'])) {
            if ($_POST['newPassword'] !== $_POST['repeatPassword']) {
                $this->errorMessage = 'Пароли должны совпадать!';
                return;
            }

            $connect->addTo('users', [
                $_POST['username'] => 'username',
                $_POST['newPassword'] => 'password'
            ]);
        }
    }
}
