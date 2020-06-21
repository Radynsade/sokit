<?php

/*
    SQL Query builder
*/

namespace core\tools;

final class Query {
    private $table;
    private $action;
    private $values;
    private $order;
    private $where;
    public $sql;

    // Constructor
    public function __construct(string $tableName) {
        $this->table = '`' . $tableName . '`';
    }

    // Factory
    public static function write(string $tableName) : object {
        return new Query($tableName);
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
        $conditions = '';

        foreach ($fieldsToValues as $field => $value) {
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
    public function insert() : object {
        $values = !empty($this->values) ? $this->joinAssoc($this->values) : '*';
        $this->action = 'insert';
        $this->sql = trim("INSERT INTO {$this->table} SET {$values}") . ';';
        return $this;
    }

    public function get() : object {
        $values = !empty($this->values) ? $this->joinValues($this->values) : '*';
        $this->action = 'get';
        $this->sql = trim("SELECT {$values} FROM {$this->table} {$this->where} {$this->order}") . ';';
        return $this;
    }

    /*
        Internal
    */
    private function joinValues(array $values) : string {
        return '`' . implode('`, `', $values) . '`';
    }

    private function joinAssoc(array $assoc) : string {
        $sql = '';

        foreach ($assoc as $field => $value) {
            $value = $this->connect->real_escape_string($value);
            $sql .= "`{$field}`='$value', ";
        }

        return substr($sql, 0, -2);
    }
}
