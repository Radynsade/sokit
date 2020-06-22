<?php

namespace views\Login;

use core\tools\Tools;
use modules\Auth\Auth;

class Enter {
    public function __construct() {
        if (!Auth::enter($_POST['username'], $_POST['password'], '/')) {
            $this->errorMessage = Auth::$error;
            return;
        };
    }
}
