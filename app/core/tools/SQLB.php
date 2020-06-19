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
    public function where(array $fieldsToValues) : object {
        $conditions = '';

        foreach ($fieldsToValues as $field => $value) {
            $conditions .= "`{$field}` = '{$value}' AND";
        }

        $conditions = substr($conditions, 0, -4);

        $this->where = "WHERE {$conditions}";
        return $this;
    }

    /*
        Actions
    */
    public function insert() : object {
        $this->action = 'insert';
        return $this;
    }

    public function get() : object {
        $values = !empty($this->values) ? $this->joinValues($this->values) : '*';
        $this->action = 'get';
        $this->sql = "SELECT {$values} FROM {$this->table} {$this->where};";
        return $this;
    }

    /*
        Internal
    */
    private function joinValues(array $values) : string {
        return '`' . implode('`, `', $this->values) . '`';
    }
}
