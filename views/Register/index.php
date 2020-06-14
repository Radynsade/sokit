<?php

namespace views\Register;

use core\Page;

final class Register extends Page {
    public $errorMessage;

    public function __construct() {
        $this->title = 'Регистрация';
        $this->description = 'Страница регистрации';
        $this->keywords = 'вход, страница, регистрация, регистрации, новый, аккаунт, профиль';
        $this->onFormSubmit();
        $this->setContent('RegForm.phtml');
    }

    private function onFormSubmit() : void {
        if (!empty($_POST['completeReg'])) {
            if ($_POST['newPassword'] !== $_POST['repeatPassword']) {
                $this->errorMessage = 'Пароли должны совпадать!';
                return;
            }

            global $connect;

            $connect->getFrom('users', ['username'], [
                'where' => [$_POST['newUsername'] => 'username'],
                'orderBy' => ['id' => 'desc']
            ]);

            $connect->addTo('users', [
                $_POST['username'] => 'username',
                $_POST['newPassword'] => 'password'
            ]);
        }
    }
}
