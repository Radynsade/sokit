<?php

namespace core\tools;

use mysqli;

class Data {
    public $connect;

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
    public function send(object $query)  {
        $response = $this->query($query->sql);

        if ($response->num_rows > 0) {
            if ($response->num_rows === 1) return $this->fetchOne($response);
            return $this->fetchResult($response);
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

    public function createTable(
        string $name,
        array $schema,
        string $engine = 'InnoDB'
    ) : void {
        $this->query("CREATE TABLE IF NOT EXISTS `{$name}` " . $this->schemaToSQL($schema) . " ENGINE = {$engine};");
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

    public function fetchResult(object $queryResult) : array {
        $result = [];

        if ($queryResult->num_rows > 0) {
            while ($row = $queryResult->fetch_assoc()) {
                array_push($result, $row);
                unset($data);
            }
        }

        return $result;
    }

    public function fetchOne(object $queryResult) : array {
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
