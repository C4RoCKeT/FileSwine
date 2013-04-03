<div id="alert-div">
</div>
<table id="search-results" class="table table-striped table-hover">
    <thead>
        <tr>
            <td class="icon"></td>
            <th>Share</th>
            <th>Description</th>
            <th>IP</th>
            <th >Files</th>
            <th>Total size</th>
            <th style="text-align:center;">Online</th>
            <td></td>
        </tr>
    </thead>
    <tbody>
        <?php
        if (!empty($results)) {
            foreach ($results as $result) {
                ?>
                <tr>
                    <td><span class="cus-house"></span></td>
                    <td><a href="fileswine://<?php echo $result->name; ?>"><?php echo $result->name; ?></a></td>
                    <td><?php echo $result->description; ?></td>
                    <td><?php echo long2ip($result->ip); ?></td>
                    <td><?php echo $result->files; ?></td>
                    <td data="<?php echo $result->size; ?>"><?php echo parse_file_size($result->size); ?></td>
                    <td class="status"><div title="<?php echo $result->last_seen; ?>" class="<?php echo $result->online ? 'online' : 'offline'; ?>"></div></td>
                    <td>
                        <div class="btn-group">
                            <button data="<?php echo $result->ip; ?>" class="btn reindex-button">Re-index</button>
                            <button class="btn dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <a href="#" class="ping-button" data="<?php echo $result->ip; ?>">Ping</a>
                                <a href="#" class="remove-button" data="<?php echo $result->ip; ?>">Remove</a>
                            </ul>
                        </div>
                    </td>
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
    </tbody>
</table>
<script >
    $(function() {
        $(".reindex-button").click(function() {
            $.get('ajax.php',{
                action: 'reindex',
                share: $(this).attr('data')
            }, function (data) {
                $("#alert-div").append(data);
            });
        });
        $(".ping-button").click(function() {
            $.get('ajax.php',{
                action: 'ping',
                share: $(this).attr('data') 
            }, function (data) {
                $("#alert-div").append(data);
            });
        });
        $(".remove-button").click(function() {
            var self = $(this);
            $.get('ajax.php',{
                action: 'remove',
                share: $(this).attr('data') 
            }, function (data) {
                self.parent().parent().parent().parent().remove();
                $("#alert-div").append(data);
            });
        });
    });
</script>