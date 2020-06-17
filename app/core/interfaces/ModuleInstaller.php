<?php

namespace core\interfaces;

interface ModuleInstaller {
    public function __construct();

    public function deploy() : void;

    public function remove() : void;
}
