<table id="search-results" class="table table-striped table-hover">
    <thead>
        <tr>
            <td class="icon"></td>
            <th>File name</th>
            <th class="{sorter: 'digit'}">Size</th>
            <th>Share name</th>
            <th style="text-align:center;">Online</th>
        </tr>
    </thead>
    <tbody>
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
    </tbody>
</table>
<div class="pagination pagination-right">
    <ul>
        <li class="pagination-prev"><a href="#">Prev</a></li>
        <?php
        for ($i = 1; $i <= $pages; $i++) {
            ?>
            <li class="pagination-page"><a href="#"><?php echo $i; ?></a></li>
            <?php
        }
        ?>
        <li class="pagination-next"><a href="#">Next</a></li>
    </ul>
</div>
<script >
    var page = 1;
    var max = <?php echo $pages; ?>;
    $(function() {
        updatePagination(page);
        $('.pagination a').click(function() {
            var newPage = $(this).html();
            switch(newPage) {
                case 'Prev':
                    if(page <= 1) {
                        return false;
                    } else {
                        page = loadResults(page - 1);
                    }
                    break;
                case 'Next':
                    if(page >= max) {
                        return false;
                    } else {
                        page = loadResults(page + 1);
                    }
                    break;
                default:
                    if(page == newPage) {
                        return false;
                    }
                    page = loadResults(parseInt(newPage));
                    break;
            }
        });
    });
    
    function loadResults(page) {
        updatePagination(page);
        $.get('ajax.php',{
            search: "<?php echo!empty($_GET['search']) ? $_GET['search'] : ''; ?>",
            extension: "<?php echo!empty($_GET['extension']) ? $_GET['extension'] : ''; ?>",
            operand: "<?php echo!empty($_GET['operand']) ? $_GET['operand'] : ''; ?>",
            file_size: "<?php echo!empty($_GET['file_size']) ? $_GET['file_size'] : ''; ?>",
            action: "<?php echo!empty($_GET['action']) ? $_GET['action'] : ''; ?>",
            page: page
        } ,function(data) {
            $('#search-results tbody').html(data);
        });
        return page;
    }
    
    function updatePagination(page) {
        if(page <= 1) {
            $('.pagination-prev').addClass('disabled');
        } else {
            $('.pagination-prev').removeClass('disabled');
        }
        if(page >= max) {
            $('.pagination-next').addClass('disabled');
        } else {
            $('.pagination-next').removeClass('disabled');
        }
        $('.pagination-page').each(function() {
            var number = $(this).find('a').html()
            if((number < page - 4 || number > page + 4) && (number != 1 && number != max)) {
                $(this).hide();
            } else {
                $(this).show();
            }
            if($(this).find('a').html() == page) {
                $(this).addClass('active');
            } else {
                $(this).removeClass('active');
            }
        });
    }
</script>