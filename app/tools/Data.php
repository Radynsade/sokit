<?php

class Data {
    public $db;

    // Database constructor
    public function __construct($host, $user, $password, $name, $schema) {
        $this->db = new mysqli($host, $user, $password);

        if ($this->db->connect_error) die('Соединениться не удалось: ' . $this->$db->connect_error);

        $this->db->set_charset('utf8');

        if ($this->db->)
    }
}
