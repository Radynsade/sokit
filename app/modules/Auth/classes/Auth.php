<?php

namespace modules\Auth;

use core\tools\Tools;

class Auth {
    public static $error;
    public static $crypter;

    public static function hashPassword(string $password) : string {
        return password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
    }

    public static function verifyPassword(
        string $password,
        string $hash
    ) : bool {
        return password_verify($password, $hash);
    }

    public static function createUser(
        string $username,
        string $newPassword,
        string $repeatPassword
    ) : bool {
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
            'username' => $encryptedLogin,
            'password' => Auth::hashPassword($newPassword),
            'last_visit' => Tools::getNow()
        ]);

        return true;
    }

    public static function enter(
        string $username,
        string $password,
        string $location
    ) : bool {
        global $connect;
        $userData = Auth::getUserData($username);

        if (empty($userData)) {
            Auth::$error = 'Такого пользователя не существует!';
            return false;
        }

        if (!Auth::verifyPassword($password, $userData['password'])) {
            Auth::$error = 'Неверный пароль!';
            return false;
        }

        $connect->update('users', [
            'last_visit' => Tools::getNow()
        ], 'id', $userData['id']);

        $_SESSION['user'] = $userData['id'];
        Tools::redirect($location);
    }

    public static function isAuthorized() : bool {
        return !empty($_SESSION['user']) ? true : false;
    }

    public static function exit(string $location) : void {
        unset($_SESSION['user']);
        session_destroy();
        Tools::redirect($location);
    }

    private static function getUserData(string $username) : array {
        global $connect;
        $encryptedLogin = Auth::$crypter->encrypt($username);
        $userData = $connect->getFrom('users', ['id', 'username', 'password'], [
            'where' => ['username', $encryptedLogin]
        ]);

        return !empty($userData) ? $userData[0] : [];
    }
}
