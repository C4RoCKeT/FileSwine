<?php

class Database_Model {

    public $pdo;

    function __construct() {
        $host = 'localhost';
        $database = 'fileswine';
        $username = '';
        $password = '';
        $this->pdo = new PDO('mysql:host=' . $host . ';dbname=' . $database, $username, $password);
    }

    function query($query) {
        $result = $this->pdo->query($query);
        return is_bool($result) ? $result : $result->fetchAll(PDO::FETCH_OBJ);
    }

    function execute($sth, $data, $fetch = false) {
        $result = $sth->execute($data);
        if ($fetch) {
            return $sth->fetchAll(PDO::FETCH_OBJ);
        } else {
            return $result;
        }
    }

    function truncate($table_name) {
        $this->query('TRUNCATE `' . $table_name . '`');
    }

}

?>