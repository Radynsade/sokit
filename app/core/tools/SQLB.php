<?php

/*
    SQL Query builder
*/

namespace core\tools;

final class SQLB {
    private $table;
    private $action;
    private $values;
    private $order;
    private $where = [];
    public $sql;

    // Constructor
    public function __construct(string $tableName) {
        $this->table = '`' . $tableName . '`';
    }

    // Factory
    public static function write(string $tableName) : object {
        return new SQLB($tableName);
    }

    // Set values or fields => values
    public function data(array $values) : object {
        $this->values = $values;
        return $this;
    }

    /*
        Conditions & additional
    */
    public function where() {

    }

    /*
        Actions
    */
    public function insert() : string {

    }

    public function get() : object {
        $values = !empty($this->values) ? $this->joinValues($this->values) : '*';

        $this->sql = "SELECT {$values} FROM {$this->table};";
        return $this;
    }

    /*
        Internal
    */
    private function joinValues(array $values) : string {
        return '`' . implode('`, `', $this->values) . '`';
    }
}
