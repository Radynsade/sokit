<?php

namespace core;

use tools\Data;

class Installer {
    public static function init(array $config) : void {
        if (!Installer::isInstalled()) {
            Installer::deploy($config);
        }
    }

    private static function deploy(array $config) : void {
        Installer::deployDatabase(
            $config['database']['host'],
            $config['database']['user'],
            $config['database']['password'],
            $config['database']['name'],
            $config['database']['tables']
        );

        $installFile = fopen('installed', 'w');
        fclose($installFile);
        unset($installFile);
    }

    private static function isInstalled() : bool {
        return file_exists('installed');
    }

    private static function deployDatabase(
        string $host,
        string $user,
        string $password,
        string $name,
        array $tables
    ) : void {
        $connect = new Data($host, $user, $password);
        $connect->setDatabase($name);

        foreach ($tables as $name => $schema) {
            $connect->createTable($name, $schema);
        }

        $connect->close();
        unset($connect);
    }
}
