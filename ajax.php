<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


if (!empty($_GET['action'])) {
    require 'model/class.database.model.php';
    $database = new Database_Model();
    switch ($_GET['action']) {
        case 'search':
            include 'controller/class.search.controller.php';
            $search_controller = new Search_Controller();
            $searches = $search_controller->get_searches();
            $search_controller->show_search_results(true);
            break;
        case 'reindex':
            include 'controller/class.share.controller.php';
            $share_controller = new Share_Controller();
            $share_controller->reindex_share($_GET['share']);
            break;
        case 'ping':
            include 'controller/class.share.controller.php';
            $share_controller = new Share_Controller();
            $share_controller->ping_share($_GET['share']);
            break;
        case 'remove':
            include 'controller/class.share.controller.php';
            $share_controller = new Share_Controller();
            $share_controller->remove_share($_GET['share']);
            break;
    }
}
?>