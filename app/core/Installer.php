<?php

namespace core;

use core\tools\Data;
use DirectoryIterator;

final class Installer {
    public static function init(array $config) : void {
        if (!Installer::isDeployed()) {
            Installer::deploy($config);
        } else {
            echo "Website is already deployed\n";
        }
    }

    private static function deploy(array $config) : void {
        Installer::deployModules($config);

        $installFile = fopen('installed', 'w');
        fclose($installFile);
        unset($installFile);

        echo "Deployment completed\n";
    }

    public static function isDeployed() : bool {
        return file_exists('installed');
    }

    private static function deployModules(array $config) : void {
        foreach (new DirectoryIterator('./app/modules/') as $file) {
            if ($file->isDot()) continue;

            if ($file->isDir()) {
                require_once "./app/modules/{$file}/install/install.php";
            }

            if (!file_exists("./app/modules/{$file}/install/installed")) {
                call_user_func(["modules\\{$file}\\Install", 'deploy'], $config);
                $installFile = fopen("./app/modules/{$file}/install/installed", 'w');
                fclose($installFile);
                unset($installFile);

                echo "{$file} deployment completed\n";
            } else {
                echo "{$file} was already deployed\n";
            }
        }
    }
}
