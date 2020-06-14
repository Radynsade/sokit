<?php

namespace tools;

class Auth {
    public static function hashPassword(string $password) : string {
        return password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
    }

    public static function verifyPassword(string $password, string $hash) : bool {
        return password_verify($password, $hash);
    }

    public static function signIn(string $user, string $location) : void {
        $_SESSION['user'] = $user;
        header('Location: /sections');
        die();
    }
}
