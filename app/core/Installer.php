<?php

namespace core;

use mysqli;
use tools\Data;

class Installer {
    public static function init() : void {
        if (!Installer::isInstalled()) {
            Installer::deploy();
        }
    }

    private static function deploy() : void {
        global $config;

        Installer::createDatabase(
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

    private static function createDatabase($host, $user, $password, $name, $tables) {
        $connect = new mysqli($host, $user, $password);

        if ($connect->connect_error) die('Соединениться не удалось: ' . $connect->connect_error);

        $connect->set_charset('utf8');
        $connect->query("CREATE DATABASE IF NOT EXISTS `{$name}`");
        $connect->close();
        unset($connect);

        Data::schemaToSQL($tables);
    }
}
