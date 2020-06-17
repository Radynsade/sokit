<?php

namespace core\interfaces;

interface ModuleInstaller {
    public function __construct(array $config);

    public function deploy() : void;

    public function remove() : void;
}
