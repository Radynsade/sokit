<?php

namespace views\Login;

use core\Page;
use libraries\Auth;

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
            global $connect;
            global $loginCrypter;

            $encryptedLogin = $loginCrypter->encrypt($_POST['username']);

            $userData = $connect->getFrom('users', ['username', 'password'], [
                'where' => ['username', $encryptedLogin]
            ]);

            if (empty($userData)) {
                $this->errorMessage = 'Такого пользователя не существует!';
                return;
            }

            if (!Auth::verifyPassword($_POST['password'], $userData['password'])) {
                $this->errorMessage = 'Неверный пароль!';
                return;
            }

            Auth::signIn($encryptedLogin, '/');
        }
    }

    private function beforeLoad() : void {
        if (!empty($_SESSION['user'])) {
            header('Location: /');
            die();
        };
    }
}
