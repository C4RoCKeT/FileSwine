<?php

class Dashboard_Controller {

    function __construct() {
        require 'model/class.share.model.php';
        $this->share = new Share_Model();
        require 'model/class.dashboard.model.php';
        $this->dashboard = new Dashboard_Model();
    }

    function show_dashboard() {
        $avg_sizes = $this->get_shares_avg_filesize();
        $system = $this->get_system_status();
        include 'view/dashboard.php';
    }
    
    function get_system_status() {
        $system['memory'] = $this->dashboard->get_memory_status();
        return $system;
    }

    function get_shares_avg_filesize() {
        $avg_sizes = array();
        foreach ($this->share->get_shares() as $share) {
            if ($share->files > 0) {
                $avg_sizes[$share->name] = round($share->size / $share->files);
            }
        }
        arsort($avg_sizes);
        return $avg_sizes;
    }

}