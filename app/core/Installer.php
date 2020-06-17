<?php

namespace core;

use core\tools\Data;
use DirectoryIterator;

const INSTALLED_FILE = './generated/installed';
const MODULES_FILE = './generated/modules';
const MODULES_DIR = './app/modules/';

final class Installer {
    public static function install() : void {
        if (!file_exists(INSTALLED_FILE)) {
            global $config;

            Installer::createDatabase(
                $config['database']['host'],
                $config['database']['user'],
                $config['database']['password'],
                $config['database']['name']
            );

            Installer::deployModules();

            $installFile = fopen(INSTALLED_FILE, 'w');
            fclose($installFile);
            unset($installFile);
            echo "Installation completed\n";
        } else {
            echo "Website is already installed\n";
        }
    }

    public static function createDatabase(
        string $host,
        string $user,
        string $password,
        string $name
    ) : void {
        $connect = new Data($host, $user, $password);
        $connect->setDatabase($name);

        $connect->close();
        unset($connect);

        echo 'Database created';
    }

    public static function redeployModules(array $modulesList = []) : void {
        Installer::removeModules($modulesList);
        Installer::deployModules($modulesList);
    }

    public static function removeModules(array $modulesList = []) : void {
        global $config;
        $modules = Installer::readModulesFile(MODULES_FILE);

        if (empty($modulesList)) {
            foreach (new DirectoryIterator(MODULES_DIR) as $file) {
                if ($file->isDot()) continue;

                if ($file->isDir()) {
                    $moduleName = $file->getFileName();
                    $modules = Installer::removeModule($moduleName, $modules);
                }
            }
        } else {
            foreach ($modulesList as $moduleName) {
                $modules = Installer::removeModule($moduleName, $modules);
            }
        }

        Installer::updateModulesFile(MODULES_FILE, $modules);
    }

    public static function deployModules(array $modulesList = []) : void {
        global $config;
        $modules = Installer::readModulesFile(MODULES_FILE);

        if (empty($modulesList)) {
            foreach (new DirectoryIterator(MODULES_DIR) as $file) {
                if ($file->isDot()) continue;

                if ($file->isDir()) {
                    $moduleName = $file->getFileName();
                    $modules = Installer::deployModule($moduleName, $modules);
                }
            }
        } else {
            foreach ($modulesList as $moduleName) {
                $modules = Installer::deployModule($moduleName, $modules);
            }
        }

        Installer::updateModulesFile(MODULES_FILE, $modules);
    }

    private static function deployModule(string $moduleName, array $modules) : array {
        if (!in_array($moduleName, $modules)) {
            $installScript = Installer::getInstallScript($moduleName);
            $installScript->deploy();
            $modules[] = $moduleName;
            echo "{$moduleName} deployment completed\n";
        } else {
            echo "{$moduleName} was already deployed\n";
        }

        return $modules;
    }

    private static function removeModule(string $moduleName, array $modules) : array {
        if (in_array($moduleName, $modules)) {
            $installScript = Installer::getInstallScript($moduleName);
            $installScript->remove();
            array_splice($modules, array_search($moduleName, $modules), 1);
            echo "{$moduleName} has been removed\n";
        } else {
            echo "{$moduleName} is already removed\n";
        }

        return $modules;
    }

    private static function getInstallScript(string $moduleName) : object {
        require_once MODULES_DIR . "{$moduleName}/install/install.php";
        $class = "modules\\{$moduleName}\\Install";
        return new $class();
    }

    private static function readModulesFile(string $pathToFile) : array {
        if (!file_exists($pathToFile)) {
            $modulesFile = fopen($pathToFile, 'a+');
            fclose($modulesFile);
            unset($modulesFile);
        }

        return array_filter(explode("\n", file_get_contents($pathToFile)));
    }

    private static function updateModulesFile(
        string $pathToFile,
        array $modules
    ) : void {
        file_put_contents($pathToFile, implode("\n", $modules));
    }
}
