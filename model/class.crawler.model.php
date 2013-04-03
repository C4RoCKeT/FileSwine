<?php

class Crawler_Model {

    private $database;

    function __construct() {
        global $database;
        $this->database = $database;
        $this->update_share = $this->database->pdo->prepare('UPDATE `share` SET `size` = `size` + :size,`files` = `files` + :files WHERE `ip` = :ip');
        $this->insert_share = $this->database->pdo->prepare('INSERT INTO `share` (`ip`,`name`,`description`) VALUES (:ip,:name,:description)');
        $this->insert_folder = $this->database->pdo->prepare('INSERT INTO `item` (`name`,`type`,`size`) VALUES (:file,"folder",:size) ON DUPLICATE KEY UPDATE `id` = LAST_INSERT_ID(`id`)');
        $this->insert_folder_path = $this->database->pdo->prepare('INSERT INTO `path` (`item_id`,`ip`,`path`) VALUES (:itemid,:ip,:path)');
        $this->insert_file = $this->database->pdo->prepare('INSERT INTO `item` (`name`,`extension`,`size`) VALUES (:name,:extension,:size) ON DUPLICATE KEY UPDATE `id` = LAST_INSERT_ID(`id`)');
        $this->insert_file_path = $this->database->pdo->prepare('INSERT INTO `path` (`item_id`,`ip`,`path`) VALUES (:itemid,:ip,:path)');
    }

    function truncate_tables() {
        $this->database->truncate('item');
        $this->database->truncate('path');
        $this->database->truncate('share');
        /* $this->database->truncate('search'); */
    }

    function update_share($size, $files, $ip) {
        $this->database->execute($this->update_share, array(':size' => $size, ':files' => $files, ':ip' => $ip));
        /*echo $size."-".$files."-".$size;
        var_dump($this->update_share);*/
    }

    function insert_share($ip, $name, $description) {
        $this->database->execute($this->insert_share, array(':ip' => $ip, ':name' => $name, ':description' => $description));
    }

    function insert_folder_path($ip, $path) {
        $this->database->execute($this->insert_folder_path, array(
            ':itemid' => $this->database->pdo->lastInsertId(),
            ':ip' => $ip,
            'path' => $path
        ));
    }

    function insert_folder($file, $size) {
        $this->database->execute($this->insert_folder, array(
            ':file' => $file,
            ':size' => $size
        ));
    }

    function insert_file($file, $extension, $size) {
        $this->database->execute($this->insert_file, array(
            ':name' => $file,
            ':extension' => $extension,
            ':size' => $size
        ));
    }

    function insert_file_path($ip, $path) {
        $this->database->execute($this->insert_file_path, array(
            ':itemid' => $this->database->pdo->lastInsertId(),
            ':ip' => $ip,
            ':path' => $path
        ));
    }

}

?>