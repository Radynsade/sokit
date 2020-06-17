<?php

namespace modules\Auth;

use core\interfaces\ModuleInstaller;
use core\tools\Data;
use core\tools\Tools;

final class Install implements ModuleInstaller {
    private $connect;
    private $schema;

    public function __construct() {
        global $config;

        $this->schema = Tools::readJSON('./app/modules/Auth/install/schema.json');
        $this->connect = new Data(
            $config['database']['host'],
            $config['database']['user'],
            $config['database']['password'],
            $config['database']['name']
        );
    }

    public function deploy() : void {
        $this->connect->deploySchema($this->schema);
    }

    public function remove() : void {
        $this->connect->removeSchema($this->schema);
    }
}
