<?php

namespace modules\Auth;

require 'classes/Auth.php';

use libraries\Crypter;

// Username crypter
Auth::$crypter = new Crypter('AES-128-CBC','8259561259121120','Sokit2Key');
