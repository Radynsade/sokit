<?php

namespace modules\Auth;

use core\tools\Tools;
use core\tools\Query;

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
        $encryptedLogin = Auth::$crypter->encrypt($username);

        if ($newPassword !== $repeatPassword) {
            Auth::$error = 'Пароли должны совпадать!';
            return false;
        }

        $sameUsernames = Query::with('users')
            ->where(['username' => $encryptedLogin])
            ->get();

        if (!empty($sameUsernames)) {
            Auth::$error = 'Пользователь с таким именем уже существует!';
            return false;
        }

        Query::with('users')
            ->data([
                'username' => $encryptedLogin,
                'password' => Auth::hashPassword($newPassword),
                'last_visit' => Tools::getNow()
            ])
            ->insert();

        return true;
    }

    public static function enter(
        string $username,
        string $password,
        string $location
    ) : bool {
        $userData = Auth::getUserData($username);

        if (empty($userData)) {
            Auth::$error = 'Такого пользователя не существует!';
            return false;
        }

        if (!Auth::verifyPassword($password, $userData['password'])) {
            Auth::$error = 'Неверный пароль!';
            return false;
        }

        Query::with('users')
            ->data([
                'last_visit' => Tools::getNow()
            ])
            ->update();

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
        $encryptedLogin = Auth::$crypter->encrypt($username);

        $userData = Query::with('users')
            ->data(['id', 'username', 'password'])
            ->where(['username' => $encryptedLogin])
            ->get();

        var_dump($userData);

        return !empty($userData) ? $userData : [];
    }
}
