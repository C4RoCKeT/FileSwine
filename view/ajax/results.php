<?php
if (!$error && !empty($results)) {
    foreach ($results as $result) {
        $size = $result->size . ' B';
        if ($result->size / 1024 > 1)
            $size = round($result->size / 1024, 1) . ' KB';
        if ($result->size / 1024 / 1024 > 1)
            $size = round($result->size / 1024 / 1024, 1) . ' MB';
        if ($result->size / 1024 / 1024 / 1024 > 1)
            $size = round($result->size / 1024 / 1024 / 1024, 1) . ' GB';
        if ($result->size / 1024 / 1024 / 1024 / 1024 > 1)
            $size = round($result->size / 1024 / 1024 / 1024 / 1024, 1) . ' TB';
        ?>
        <tr>
            <td><span class="cus-<?php echo $result->type; ?>"></span></td>
            <td><a href="fileswine://<?php echo $result->path; ?>"><?php echo $result->item_name; ?></a></td>
            <td data="<?php echo $result->size; ?>"><?php echo $size; ?></td>
            <td><?php echo $result->share_name; ?></td>
            <td class="status"><div class="<?php echo $result->online ? 'online' : 'offline'; ?>"></div></td>
        </tr>
        <?php
    }
} else {
    if (empty($error)) {
        $error = "No results...";
    }
    ?>
    <tr>
        <td colspan="5"><?php echo $error; ?></td>
    </tr>
    <?php
}
?>