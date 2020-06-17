<?php

namespace core\interfaces;

interface ModuleInstaller {
    public static function deploy(array $config) : void;
}
