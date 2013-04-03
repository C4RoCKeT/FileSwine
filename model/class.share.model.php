<?php

class Share_Model {

    private $database;

    function __construct() {
        global $database;
        $this->database = $database;
    }

    function get_shares() {
        $query = 'SELECT * FROM `share`';
        return $this->database->query($query);
    }

    function set_online($ip, $online = true) {
        if ($online) {
            $this->database->query('UPDATE `share` SET `online` = 1, `last_seen` = "' . date('Y-m-d H:i:s') . '" WHERE `ip` = "' . $ip . '"');
        } else {
            $this->database->query('UPDATE `share` SET `online` = 0 WHERE `ip` = "' . $ip . '"');
        }
    }
    
    function remove_share($ip) {
        $this->database->query('DELETE FROM `share` WHERE `ip` = ' . $ip);
        $this->database->query('DELETE FROM `path` WHERE `ip` = ' . $ip);
    }

}

?>