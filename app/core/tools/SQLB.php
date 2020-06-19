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
    public function where() : object {

        return $this;
    }

    /*
        Actions
    */
    public function insert() : object {
        return $this->setAction(function() {
            $this->action = 'insert';
            return $this;
        });
    }

    public function get() : object {
        return $this->setAction(function() {
            $values = !empty($this->values) ? $this->joinValues($this->values) : '*';
            $this->action = 'get';
            $this->sql = "SELECT {$values} FROM {$this->table};";
            return $this;
        });
    }

    /*
        Internal
    */
    private function joinValues(array $values) : string {
        return '`' . implode('`, `', $this->values) . '`';
    }

    private function setAction(callable $function) : object {
        if (empty($this->action)) {
            return $function();
        }

        die("SQLB: Cannot set '{$this->action}' action, another action is already declared.");
    }
}
