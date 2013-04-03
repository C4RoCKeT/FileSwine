<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'model/class.database.model.php';
$database = new Database_Model();

include 'functions.php';
include 'view/header.php';
switch (!empty($_GET['action']) ? $_GET['action'] : '') {
    case 'search':
        include 'controller/class.search.controller.php';
        $search_controller = new Search_Controller();
        $search_controller->show_search_results();
        break;
    case 'advanced':
        include 'controller/class.search.controller.php';
        $search_controller = new Search_Controller();
        $search_controller->show_search_input(true);
        break;
    case 'shares':
        include 'controller/class.share.controller.php';
        $share_controller = new Share_Controller();
        $share_controller->show_shares();
        break;
    case 'dashboard':
        include 'controller/class.dashboard.controller.php';
        $dashboard_controller = new Dashboard_Controller();
        $dashboard_controller->show_dashboard();
        break;
    case '404':
        include 'view/404.php';
        break;
    default:
        include 'controller/class.search.controller.php';
        $search_controller = new Search_Controller();
        $search_controller->show_search_input();
        break;
}
include 'view/footer.php';
?>