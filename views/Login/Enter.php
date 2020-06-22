<?php

namespace views\Login;

use modules\Auth\Auth;
use core\Post;
use core\tools\Tools;

class Enter {
    public function __construct() {
        if (!Auth::enter($_POST['username'], $_POST['password'], '/')) {
            Post::send('AuthError', Auth::$error);
            Tools::redirect('/login');
        };
    }
}
