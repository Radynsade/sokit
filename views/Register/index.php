<?php

namespace views\Register;

use core\Page;
use tools\Auth;

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
            global $connect;

            if ($_POST['newPassword'] !== $_POST['repeatPassword']) {
                $this->errorMessage = 'Пароли должны совпадать!';
                return;
            }

            $sameUsernames = $connect->getFrom('users', ['username'], [
                'where' => ['username', $_POST['username']],
                'orderBy' => ['id', 'DESC']
            ]);

            if (!empty($sameUsernames)) {
                $this->errorMessage = 'Пользователь с таким именем уже существует!';
                return;
            }

            $connect->addTo('users', [
                $_POST['username'] => 'username',
                Auth::hashPassword($_POST['newPassword']) => 'password'
            ]);

            Auth::signIn($_POST['username'], '/sections');
        }
    }
}
