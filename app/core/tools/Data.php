<?php

namespace core\tools;

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

    public function query($sql) {
        $query = $this->connect->query($sql);

        if (!$query) {
            echo "Ошибка MySQL: {$this->connect->error}";
            return null;
        }

        return $query;
    }

    public function setDatabase(string $name) : void {
        $this->query("CREATE DATABASE IF NOT EXISTS `{$name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        $this->connect->select_db($name);
    }

    public function getAllFrom(
        string $tableName,
        string $orderBy = 'id',
        string $order = 'DESC'
    ) : array {
        return Data::fetchResult($this->query("SELECT * FROM `{$tableName}` ORDER BY `{$orderBy}` {$order}"));
    }

    public function getFrom(
        string $tableName,
        array $columns,
        array $data = []
    ) {
        $columnsList = '';
        $where = isset($data['where']) ? " WHERE `{$data['where'][0]}` = '{$data['where'][1]}' " : '';
        $orderBy = isset($data['orderBy']) ? "ORDER BY `{$data['orderBy'][0]}` {$data['orderBy'][1]}" : '';

        if (!empty($columns)) {
            foreach ($columns as $column) {
                $columnsList .= "`{$column}`, ";
            }
        } else {
            $columnsList = '*';
        }

        $columnsList = substr($columnsList, 0, -2);

        return Data::fetchResult(
            $this->query("SELECT {$columnsList} from `{$tableName}`" . $where . $orderBy . ';')
        );
    }

    public function addTo(
        string $tableName,
        array $valuesToFields
    ) : bool {
        $values = '';
        $fields = '';

        foreach ($valuesToFields as $value => $field) {
            $values .= "'{$value}', ";
            $fields .= "`{$field}`, ";
        }

        $values = substr($values, 0, -2);
        $fields = substr($fields, 0, -2);

        return $this->query("INSERT INTO `{$tableName}` ({$fields}) VALUES ({$values});");
    }

    public function createTable(
        string $name,
        array $schema,
        string $engine = 'InnoDB'
    ) : void {
        $this->query("CREATE TABLE IF NOT EXISTS `{$name}` " . Data::schemaToSQL($schema) . " ENGINE = {$engine};");
    }

    private static function fetchResult(object $queryResult) : array {
        $result = [];

        if ($queryResult->num_rows > 0) {
            while ($row = $queryResult->fetch_assoc()) {
                foreach ($row as $key => $value) {
                    $result[$key] = $value;
                }
            }
        }

        return $result;
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
