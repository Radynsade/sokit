<?php

namespace modules\Auth;

use core\interfaces\ModuleInstaller;
use core\tools\Data;
use core\tools\Tools;

final class Install implements ModuleInstaller {
    public static function deploy(array $config) : void {
        $schema = Tools::readJSON('./app/modules/Auth/install/schema.json');

        Data::deploySchema(
            $config['database']['host'],
            $config['database']['user'],
            $config['database']['password'],
            $config['database']['name'],
            $schema
        );
    }
}
