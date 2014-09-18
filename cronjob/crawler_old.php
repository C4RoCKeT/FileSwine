<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$timestamp = date('Y-m-d H:i:s');
$mysqli = new mysqli('localhost', 'root', 'password', 'fileswine');
$mysqli->query('TRUNCATE `item`');
$mysqli->query('TRUNCATE `path`');
$mysqli->query('TRUNCATE `share`');
$smb_ips = array();

foreach (preg_split("/((\r?\n)|(\r\n?))/", shell_exec("smbtree -bN | grep -iE '\\\\'")) as $share) {
    $share = explode("\t", trim($share));
    $share[0] = trim($share[0]);
    $smb = explode('\\', substr($share[0], 2));
    //Check if the line returned contains a share
    if (count($smb) > 1) {
        //Check if the machine was allowed for indexing
        if (isset($smb_ips[$smb[0]])) {
            //Check if the share is allowed for indexing
            if (isset($share[1]) && trim($share[1]) == 'noindex' || substr($share[0], -1) == '$') {
                echo "\tSkipping " . $share[0] . "\n\r";
            } else {
                echo "\tIndexing " . $share[0] . "\n\r";
                shell_exec('mkdir -p "/tmp/fileswine/' . $smb_ips[$smb[0]] . '/' . $smb[1] . '"');
                shell_exec("smbmount \"//" . $smb_ips[$smb[0]] . "/" . $smb[1] . "\" \"/tmp/fileswine/" . $smb_ips[$smb[0]] . "/" . $smb[1] . "\" -o guest,ro 2>/dev/null");
                $share_data = list_contents('/tmp/fileswine/' . $smb_ips[$smb[0]] . '/' . $smb[1], $smb_ips[$smb[0]], $smb[0]);
                $mysqli->query('UPDATE `share` SET `size` = `size` + ' . $share_data['size'] . ',`files` = `files` + ' . $share_data['files'] . ' WHERE `ip` = ' . ip2long($smb_ips[$smb[0]]));
                shell_exec("umount -f -l \"/tmp/fileswine/" . $smb_ips[$smb[0]] . "/" . $smb[1] . "\" 2>/dev/null");
            }
        }
    } else {
        //The line doesn't contain a share; it contains a machine
        if (!empty($smb[0])) {
            $index = true;
            $description = '';
            if (isset($share[2])) {
                $description = trim($share[2]);
                $index = $description != 'noindex';
            }
            if ($index) {
                if (!isset($smb_ips[$smb[0]])) {
                    preg_match_all('/\d+\.\d+\.\d+\.\d+/', shell_exec("nmblookup -I " . $smb[0]), $matches);
                    $smb_ips[$smb[0]] = $matches[0][1];
                    $mysqli->query('INSERT INTO `share` (`ip`,`name`,`description`) VALUES (' . ip2long($smb_ips[$smb[0]]) . ',"' . $smb[0] . '","' . $description . '")');
                    echo 'Found machine: ' . $smb[0] . "\t\t" . $description . "\n\r";
                } else {
                    echo 'Already known machine: ' . $smb[0] . "\n\r";
                }
            } else {
                echo 'Skipping machine: ' . $smb[0] . "\n\r";
            }
        }
    }
}

function list_contents($dir, $ip, $smb_name) {
    global $timestamp;
    global $mysqli;
    global $item_id;
    $ip_long = ip2long($ip);
    $total_size = 0;
    $files = 0;
    if ($handle = opendir($dir)) {
        while (false !== ($file = readdir($handle))) {
            preg_match("/(^(([\.]){1,2})$|(\.(svn|git|md|message|debug|nfo|sfv))|(Thumbs\.db|\.DS_STORE))$/iu", $file, $skip);
            if (!$skip) {
                $path = str_replace('/tmp/fileswine/', '', $dir) . '/' . $file;
                if (is_dir($dir . '/' . $file)) {
                    $folder_data = list_contents($dir . '/' . $file, $ip, $smb_name);
                    if ($folder_data['size'] > 0) {
                        $total_size += $folder_data['size'];
                        $files += $folder_data['files'];
                        $mysqli->query('INSERT INTO `item` (`name`,`type`,`size`) VALUES ("' . $file . '","folder",' . $folder_data['size'] . ') ON DUPLICATE KEY UPDATE `id` = LAST_INSERT_ID(`id`)');
                        $mysqli->query('INSERT INTO `path` (`item_id`,`ip`,`path`) VALUES (' . $mysqli->insert_id . ', ' . $ip_long . ',"' . str_replace('/tmp/fileswine/' . $ip, $smb_name, $dir) . '/' . $file . '");');
                    }
                } else {
                    $file_size = filesize($dir . '/' . $file);
                    if ($file_size > 0) {
                        $total_size += $file_size;
                        $files++;
                        $mysqli->query('INSERT INTO `item` (`name`,`extension`,`size`) VALUES ("' . $file . '","' . pathinfo($dir . '/' . $file, PATHINFO_EXTENSION) . '",' . $file_size . ') ON DUPLICATE KEY UPDATE `id` = LAST_INSERT_ID(`id`)');
                        $mysqli->query('INSERT INTO `path` (`item_id`,`ip`,`path`) VALUES (' . $mysqli->insert_id . ', ' . $ip_long . ',"' . str_replace('/tmp/fileswine/' . $ip, $smb_name, $dir) . '/' . $file . '");');
                    }
                }
            }
        }
        closedir($handle);
    }
    return array('size' => $total_size, 'files' => $files);
}
?>
