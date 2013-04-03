<?php

function parse_file_size($size_in_bytes) {
    $size = $size_in_bytes . ' B';
    if ($size_in_bytes / 1024 > 1) {
        $size = round($size_in_bytes / 1024, 1) . ' KB';
        if ($size_in_bytes / 1024 / 1024 > 1) {
            $size = round($size_in_bytes / 1024 / 1024, 1) . ' MB';
            if ($size_in_bytes / 1024 / 1024 / 1024 > 1) {
                $size = round($size_in_bytes / 1024 / 1024 / 1024, 1) . ' GB';
                if ($size_in_bytes / 1024 / 1024 / 1024 / 1024 > 1) {
                    $size = round($size_in_bytes / 1024 / 1024 / 1024 / 1024, 1) . ' TB';
                }
            }
        }
    }
    return $size;
}

?>
