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

        $installFile = fopen('./generated/installed', 'w');
        fclose($installFile);
        unset($installFile);

        echo "Deployment completed\n";
    }

    public static function isDeployed() : bool {
        return file_exists('./generated/installed');
    }

    private static function deployModules(array $config) : void {
        $modulesFile = fopen('./generated/modules', 'a+');
        fclose($modulesFile);
        unset($modulesFile);

        $modules = explode("\n", file_get_contents('./generated/modules'));

        foreach (new DirectoryIterator('./app/modules/') as $file) {
            if ($file->isDot()) continue;

            if ($file->isDir()) {
                require_once "./app/modules/{$file}/install/install.php";
            }

            if (!in_array($file->getFileName(), $modules)) {
                call_user_func(["modules\\{$file}\\Install", 'deploy'], $config);
                $modules[] = $file->getFilename();
                echo "{$file} deployment completed\n";
            } else {
                echo "{$file} was already deployed\n";
            }
        }

        file_put_contents('./generated/modules', implode("\n", $modules));
    }
}
