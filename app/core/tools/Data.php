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

    // Send any query and give response
    public function send(string $sql)  {
        $response = $this->query($sql);

        if ($response->num_rows > 0) {
            if ($response->num_rows === 1) return Data::fetchOne($response);
            return Data::fetchResult($response);
        }
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
            $columnsList = substr($columnsList, 0, -2);
        } else {
            $columnsList = '*';
        }

        return Data::fetchResult(
            $this->query("SELECT {$columnsList} from `{$tableName}`" . $where . $orderBy . ';')
        );
    }

    public function addTo(
        string $tableName,
        array $fieldsToValues
    ): bool {
        $newValues = '';

        foreach ($fieldsToValues as $field => $value) {
            $value = $this->connect->real_escape_string($value);
            $newValues .= "`{$field}`='$value', ";
        }

        $newValues = substr($newValues, 0, -2);

        return $this->query("INSERT INTO `{$tableName}` SET {$newValues};");
    }

    public function update(
        string $tableName,
        array $fieldsToValues,
        string $conditionField,
        $conditionValue
    ) : bool {
        $newValues = '';

        foreach ($fieldsToValues as $field => $value) {
            $value = $this->connect->real_escape_string($value);
            $newValues .= "`{$field}`='$value', ";
        }

        $newValues = substr($newValues, 0, -2);
        $sql = "UPDATE `{$tableName}` SET {$newValues} WHERE `{$conditionField}`='{$conditionValue}';";

        return $this->query($sql);
    }

    public function createTable(
        string $name,
        array $schema,
        string $engine = 'InnoDB'
    ) : void {
        $this->query("CREATE TABLE IF NOT EXISTS `{$name}` " . Data::schemaToSQL($schema) . " ENGINE = {$engine};");
    }

    public function removeTable(string $table) : void {
        $this->query("DROP TABLE {$table};");
    }

    public function deploySchema(array $tables) : void {
        foreach ($tables as $name => $schema) {
            $this->createTable($name, $schema);
        }
    }

    public function removeSchema(array $tables) : void {
        foreach ($tables as $name => $schema) {
            $this->removeTable($name);
        }
    }

    private static function fetchResult(object $queryResult) : array {
        $result = [];

        if ($queryResult->num_rows > 0) {
            while ($row = $queryResult->fetch_assoc()) {
                // $data = [];

                // foreach ($row as $key => $value) {
                    // $data[$key] = $value;
                // }

                array_push($result, $row);
                unset($data);
            }
        }

        return $result;
    }

    private static function fetchOne(object $queryResult) : array {
        return $queryResult->fetch_assoc();
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
