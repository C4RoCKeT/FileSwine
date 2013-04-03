<?php

class Search_Controller {

    private $search;

    function __construct() {
        require 'model/class.search.model.php';
        $this->search = new Search_Model();
    }

    function show_search_input($advanced = false) {
        $searches = $this->get_searches();
        if ($advanced)
            include 'view/advanced-search.php';
        else
            include 'view/search.php';
    }

    function show_search_results($ajax = false) {
        $error = false;
        if (isset($_GET['search']) && trim($_GET['search']) != '') {
            $search = trim($_GET['search']);
            if (strlen($search) < 3) {
                $error = 'Please use a search term of 3 characters or more.';
            }
        } else {
            $error = 'You can\'t search for nothing!';
        }
        if (isset($_GET['extension']) && trim($_GET['extension']) != '') {
            $conditions['extension'] = trim($_GET['extension']);
        } else {
            $conditions['extension'] = '';
        }
        if (isset($_GET['file_size']) && intval(trim($_GET['file_size']) >= 0)) {
            $conditions['file_size'] = intval(trim($_GET['file_size']));
        } else {
            $conditions['file_size'] = '';
        }
        if (isset($_GET['operand']) && trim($_GET['operand']) != '') {
            $conditions['operand'] = trim($_GET['operand']);
        } else {
            $conditions['operand'] = '';
        }
        if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] >= 0) {
            if ($_GET['page'] > 0)
                $_GET['page']--;
            $conditions['start'] = $_GET['page'] * 50;
        } else {
            $conditions['start'] = 0;
        }
        if (empty($error)) {
            $results = $this->search->search_items($search, $conditions);
            $rows = $this->search->count_results($search);
        }
        $pages = !empty($rows) ? $rows[0]->rows / 50 : 0;
        if (!$ajax)
            include 'view/results.php';
        else
            include 'view/ajax/results.php';
    }

    function get_searches() {
        $result = $this->search->get_searches();
        $searches = array();
        if (!empty($result)) {
            foreach ($result as $search) {

                $searches[] = (string) $search->name;
            }
        }
        return $searches;
    }

}
