<?php

class Share_Controller {

    private $share;

    function __construct() {
        require 'model/class.share.model.php';
        $this->share = new Share_Model();
    }

    function show_shares() {
        $results = $this->share->get_shares();
        include 'view/shares.php';
    }

    function reindex_share($ip) {
        $title = 'Heads up!';
        $message = 'This is still under construction.';
        include 'view/ajax/alert-info.php';
    }

    function remove_share($ip) {
        $this->share->remove_share($ip);
        $title = 'Success!';
        $message = 'The share has successfully been removed.';
        include 'view/ajax/alert-success.php';
    }

    function ping_share($ip) {
        $message = shell_exec('fping -er 1 ' . long2ip($ip));
        $result = explode(' ', $message);
        if (trim($result[count($result) - 1]) != 'unreachable') {
            $this->share->set_online($ip);
            $title = 'PONG!';
            include 'view/ajax/alert-success.php';
        } else {
            $title = 'AWWWWW!';
            $this->share->set_online($ip, false);
            include 'view/ajax/alert-error.php';
        }
    }

}
