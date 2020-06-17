<?php

namespace modules\Auth;

use core\interfaces\ModuleInstaller;
use core\tools\Data;
use core\tools\Tools;

final class Install implements ModuleInstaller {
    private $connect;

    public function __construct(array $config) {
        $this->connect = new Data(
            $config['database']['host'],
            $config['database']['user'],
            $config['database']['password'],
            $config['database']['name']
        );
    }

    public function deploy() : void {
        $schema = Tools::readJSON('./app/modules/Auth/install/schema.json');
        $this->connect->deploySchema($schema);
    }

    public function remove() : void {

    }
}
