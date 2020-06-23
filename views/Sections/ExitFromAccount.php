<?php

namespace views\Sections;

use modules\Auth\Auth;

class ExitFromAccount {
    public function __construct() {
        Auth::exit('/');
    }
}
