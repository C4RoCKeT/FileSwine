<?php

class Dashboard_Model {

    private $database;

    function __construct() {
        global $database;
        $this->database = $database;
    }

    function get_memory_status() {
        $status = array();
        $status['used']['percentage'] = number_format((int) shell_exec("free | grep 'cache:' | awk '{print $3/($4+$3) * 100.0}'"), 1);
        return $status;
    }

}

?>