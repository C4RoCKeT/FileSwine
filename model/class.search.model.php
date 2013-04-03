<?php

class Search_Model {

    private $database;

    function __construct() {
        global $database;
        $this->database = $database;
    }

    /**
     * Searches for items in the database.
     *
     * You can search based on name,size,extension and ip address.
     *
     * @since 1.0.0
     *
     * @param string $search The name of the item to search for.
     * @param string $extension The extension of the item to search for.
     * @param int $file_size The size of the file.
     * @param string $operand The operand used to match the file and the size by.
     * @return object An object containt the search results.
     */
    function search_items($search, $conditions, $count = false) {
        if (!$count) {
            $query = 'SELECT `path`.`path` AS `path`,`item`.`name` AS "item_name",`item`.`size`,`share`.`name` AS "share_name",`online`,`share`.`last_seen`,`item`.`type`';
            $query.=' FROM `item` LEFT JOIN `path` ON `path`.`item_id` = `item`.`id` LEFT JOIN `share` ON `path`.`ip` = `share`.`ip` WHERE 1=1';
        } else {
            $query = 'SELECT COUNT(*) as `rows`';
            $query.=' FROM `item` LEFT JOIN `path` ON `path`.`item_id` = `item`.`id` LEFT JOIN `share` ON `path`.`ip` = `share`.`ip` WHERE 1=1';
        }
        $data = array();
        /*
          Normal text search:
         */
        if ($search != '') {
            $key_words = explode(' ', $search);
            foreach ($key_words as $i => $key_word) {
                $data[':search' . $i] = '%' . $key_word . '%';
                $query.= ' AND `item`.`name` LIKE :search' . $i;
            }
        }
        /**
         * Let the user choose from a predefined set of extensions or allow them
         * to search for a custom extension
         */
        if (!empty($conditions['extension'])) {
            switch ($conditions['extension']) {
                case 'video':
                    $query.= ' AND `extension` IN ("avi", "mkv", "mp4", "mpeg")';
                    break;
                case 'audio':
                    $query.= ' AND `extension` IN ("mp3", "flac", "wav")';
                    break;
                case 'images':
                    $query.= ' AND `extension` IN ("png", "jpg", "jpeg", "gif", "psd")';
                    break;
                case 'documents':
                    $query.= ' AND `extension` IN ("doc", "docx", "xls", "xlxs", "ppt", "pptx", "pdf", "txt")';
                    break;
                case 'folders':
                    $query.= ' AND `type` = "folder"';
                    break;
                default:
                    $data[':extension'] = $conditions['extension'];
                    $query.= ' AND `extension` = :extension';
                    break;
            }
        }
        /**
         * Allow the user to use a custom operand. Use >= as default.
         */
        if (isset($conditions['file_size'])) {
            switch ($conditions['operand']) {
                case 'greater':
                    $operand = '>';
                    break;
                case 'greaterorequal':
                default:
                    $operand = '>=';
                    break;
                case 'smaller':
                    $operand = '<';
                    break;
                case 'smallerorequal':
                    $operand = '<=';
                    break;
            }
            $conditions['file_size'] = intval($conditions['file_size']) * 1024 * 1024;
            $data[':file_size'] = $conditions['file_size'];
            $query.= ' AND `item`.`size` ' . $operand . ' :file_size';
        }
        if (!$count) {
            $conditions['start'] = empty($conditions['start']) ? 0 : $conditions['start'];
            $conditions['order_by'] = empty($conditions['order_by']) ? 'name' : $conditions['order_by'];
            switch ($conditions['order_by']) {
                case 'name':
                    $order_by = '`item`.`name`';
                    break;
                case 'size':
                    $order_by = '`item`.`size`';
                    break;
                case 'share':
                    $order_by = '`share`.`name`';
                    break;
            }
            $conditions['order'] = empty($conditions['order']) ? 'ASC' : $conditions['order'];
            $query.= ' ORDER BY ' . $order_by . ' ' . $conditions['order'] . ' LIMIT ' . $conditions['start'] . ',50';
            if ($search != '' && !$conditions['start']) {
                $this->add_search($search);
            }
        }
        $sth = $this->database->pdo->prepare($query);
        return $this->database->execute($sth, $data, true);
    }

    /**
     * Searches for items in the database.
     *
     * You can search based on name,size,extension and ip address.
     *
     * @since 1.0.0
     *
     * @param string $search The name of the item to search for.
     * @param string $extension The extension of the item to search for.
     * @param int $file_size The size of the file.
     * @param string $operand The operand used to match the file and the size by.
     * @return object An object containt the search results.
     */
    function count_results($search) {
        return $this->search_items($search, array(), true);
    }

    function get_searches() {
        $query = 'SELECT `name` FROM `search` ORDER BY `count` DESC LIMIT 0,50';
        return $this->database->query($query);
    }

    function add_search($search) {
        $query = 'INSERT INTO `search` (`name`) VALUES (:search) ON DUPLICATE KEY UPDATE `count` = `count` + 1';
        $sth = $this->database->pdo->prepare($query);
        $this->database->execute($sth, array(':search' => $search));
    }

}

?>
