<?php

namespace modules\Auth;

use core\interfaces\ModuleInstaller;
use core\tools\Tools;

final class Install implements ModuleInstaller {
    private $schema;

    public function __construct() {
        $this->schema = Tools::readJSON('./app/modules/Auth/install/schema.json');
    }

    public function deploy() : void {
        global $connect;

        $connect->deploySchema($this->schema);
    }

    public function remove() : void {
        global $connect;

        $connect->removeSchema($this->schema);
    }
}
