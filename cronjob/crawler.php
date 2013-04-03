<?php
error_reporting(-1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

set_include_path(dirname(dirname(__FILE__)));
require 'model/class.database.model.php';
$database = new Database_Model();

include 'functions.php';
include 'controller/class.crawler.controller.php';
$crawler_controller = new Crawler_Controller();
echo $crawler_controller->crawl();
?>