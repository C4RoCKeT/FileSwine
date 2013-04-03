<?php

$mysqli = new mysqli('localhost', 'root', 'UBsxiWL4', 'fileswine');
$result = $mysqli->query('SELECT `ip` FROM `share`');
$ips = '';
$last_seen = date('Y-m-d H:i:s');
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $ips.=' ' . long2ip($row['ip']);
    }
    foreach (explode("\n", shell_exec('fping -a -r 0' . $ips . ' 2>&1')) as $ip) {
        $mysqli->query('UPDATE `share` SET `online` = 1, `last_seen` = "' . $last_seen . '" WHERE `ip` = "' . ip2long(trim($ip)) . '"');
    }
}
$mysqli->query('UPDATE `share` SET `online` = 0 WHERE `last_seen` != "' . $last_seen . '"');
?>