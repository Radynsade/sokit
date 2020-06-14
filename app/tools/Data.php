<?php

namespace tools;

use mysqli;

class Data {
    private $connect;

    public function __construct(
        string $host,
        string $user,
        string $password,
        string $name = null
    ) {
        $this->connect = new mysqli(
            $host,
            $user,
            $password,
            $name
        );

        if ($this->connect->connect_error) die("Соединениться не удалось: {$this->connect->connect_error}");

        $this->connect->set_charset('utf8');
    }

    public function close() : void {
        $this->connect->close();
    }

    public function query($sql) : void {
        if (!$this->connect->query($sql)) echo "Ошибка MySQL: {$this->connect->error}";
    }

    public function setDatabase(string $name) : void {
        $this->query("CREATE DATABASE IF NOT EXISTS `{$name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        $this->connect->select_db($name);
    }

    public function addTo(
        string $tableName,
        array $valuesToFields
    ) : void {
        $values = '';
        $fields = '';

        foreach ($valuesToFields as $value => $field) {
            $values .= "'{$value}', ";
            $fields .= "`{$field}`, ";
        }

        $values = substr($values, 0, -2);
        $fields = substr($fields, 0, -2);

        $this->query("INSERT INTO `{$tableName}` ({$fields}) VALUES ({$values});");
    }

    public function createTable(
        string $name,
        array $schema,
        string $engine = 'InnoDB'
    ) : void {
        $this->query("CREATE TABLE IF NOT EXISTS `{$name}` " . Data::schemaToSQL($schema) . " ENGINE = {$engine};");
    }

    private static function schemaToSQL(array $schema) : string {
        $SQLSchema = '';
        $primary = '';

        foreach ($schema as $title => $data) {
            $columnSQL = "`{$title}`";

            $columnSQL .= ' ' . strtoupper($data['type']);
            $columnSQL .= !empty($data['length']) ? "({$data['length']})" : '';
            $columnSQL .= empty($data['null']) ? ' NOT NULL' : ' NULL';
            $columnSQL .= !empty($data['auto']) ? ' AUTO_INCREMENT' : '';

            if (!empty($data['primary'])) $primary = "PRIMARY KEY (`{$title}`)";

            $SQLSchema .= $columnSQL . ',';
        }

        return "({$SQLSchema} {$primary})";
    }
}
