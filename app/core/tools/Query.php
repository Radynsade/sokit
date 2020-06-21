<?php

/*
    SQL Query builder
*/

namespace core\tools;

final class Query {
    public $table;
    public $action;
    public $values;
    public $order;
    public $where;
    public $sql;

    // Constructor
    public function __construct(string $tableName) {
        $this->table = '`' . $tableName . '`';
    }

    // Factory
    public static function with(string $tableName) : object {
        return new Query($tableName);
    }

    // Send query to the MySQL database and gives response
    // Automatic fetching returns a single associative array if there is only one row in result
    private function send(string $sql, bool $autoFetch = false) {
        global $connect;

        $response = $connect->query($sql);

        if ($response->num_rows > 0) {
            if ($response->num_rows === 1) return $connect->fetchOne($response);
            return $connect->fetchResult($response);
        }
    }

    // Set values or fields => values
    public function data(array $values) : object {
        $this->values = $values;
        return $this;
    }

    /*
        Conditions & additional
    */
    public function where(array $fieldsToValues) : object {
        global $connect;
        $conditions = '';

        foreach ($fieldsToValues as $field => $value) {
            $value = $connect->connect->real_escape_string($value);
            $conditions .= "`{$field}` = '{$value}' AND";
        }

        $conditions = substr($conditions, 0, -4);

        $this->where = "WHERE {$conditions}";
        return $this;
    }

    public function orderBy(string $field, string $order = 'desc') {
        $order = strtoupper($order);
        $this->order = "ORDER BY `{$field}` {$order}";
        return $this;
    }

    /*
        Actions
    */
    public function insert() : void {
        $values = $this->joinAssoc($this->values);
        $this->action = 'insert';
        $sql = trim("INSERT INTO {$this->table} SET {$values}") . ';';
        $this->send($sql);
    }

    public function get(bool $autoFetch = false) {
        $values = !empty($this->values) ? $this->joinValues($this->values) : '*';
        $this->action = 'get';
        $sql = trim("SELECT {$values} FROM {$this->table} {$this->where} {$this->order}") . ';';
        return $this->send($sql, $autoFetch);
    }

    public function update() : void {
        $values = $this->joinAssoc($this->values);
        $this->action = 'update';
        $sql = trim("UPDATE {$this->table} SET {$values} {$this->where}") . ';';
        $this->send($sql);
    }

    /*
        Internal
    */
    private function joinValues(array $values) : string {
        global $connect;

        $newValues = [];

        foreach ($values as $value) {
            $value = $connect->connect->real_escape_string($value);
            array_push($newValues, $value);
        }

        return '`' . implode('`, `', $newValues) . '`';
    }

    private function joinAssoc(array $assoc) : string {
        global $connect;

        $sql = '';

        foreach ($assoc as $field => $value) {
            $value = $connect->connect->real_escape_string($value);
            $sql .= "`{$field}`='$value', ";
        }

        return substr($sql, 0, -2);
    }
}
