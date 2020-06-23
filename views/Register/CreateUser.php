<?php

namespace views\Register;

use modules\Auth\Auth;
use core\Post;
use core\tools\Tools;

class CreateUser {
    public function __construct() {
        if (!Auth::createUser($_POST['username'], $_POST['newPassword'], $_POST['repeatPassword'])) {
            Post::send('AuthError', Auth::$error);
            Tools::redirect('/register');
        };

        Auth::enter($_POST['username'], $_POST['newPassword'], '/');
    }
}
