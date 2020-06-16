<?php

namespace modules\Auth;

use core\tools\Data;

class Auth {
    public static $error;
    public static $crypter;

    public static function hashPassword(string $password) : string {
        return password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
    }

    public static function verifyPassword(string $password, string $hash) : bool {
        return password_verify($password, $hash);
    }

    public static function createUser(string $username, string $newPassword, string $repeatPassword) : bool {
        global $connect;
        $encryptedLogin = Auth::$crypter->encrypt($username);

        if ($newPassword !== $repeatPassword) {
            Auth::$error = 'Пароли должны совпадать!';
            return false;
        }

        $sameUsernames = $connect->getFrom('users', ['username'], [
            'where' => ['username', $encryptedLogin],
            'orderBy' => ['id', 'DESC']
        ]);

        if (!empty($sameUsernames)) {
            Auth::$error = 'Пользователь с таким именем уже существует!';
            return false;
        }

        $connect->addTo('users', [
            $encryptedLogin => 'username',
            Auth::hashPassword($newPassword) => 'password'
        ]);

        return true;
    }

    public static function enter(string $username, string $password, string $location) {
        global $connect;
        $encryptedLogin = Auth::$crypter->encrypt($username);

        $userData = $connect->getFrom('users', ['username', 'password'], [
            'where' => ['username', $encryptedLogin]
        ]);

        if (empty($userData)) {
            Auth::$error = 'Такого пользователя не существует!';
            return false;
        }

        if (!Auth::verifyPassword($password, $userData['password'])) {
            Auth::$error = 'Неверный пароль!';
            return false;
        }

        $_SESSION['user'] = $username;
        header("Location: {$location}");
        die();
    }

    public static function exit(string $location) : void {
        unset($_SESSION['user']);
        session_destroy();
        header("Location: {$location}");
        die();
    }
}
