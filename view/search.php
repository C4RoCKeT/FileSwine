<script type="text/javascript">
    $(function() {
        $('#search').typeahead();
    });
</script>
<div id="search-div">
    <form action="/search" method="get">
        <div class="input-append">
            <input type="search" autofocus="autofocus" id="search" name="search" data-provide="typeahead" autocomplete="off" data-source="<?php echo htmlentities(json_encode($searches)); ?>"/>
            <button id="search-button" class="btn btn-primary" type="submit">Search</button>
            <a href="advanced/"><button id="advanced-button" class="btn" type="button">Advanced</button></a>
        </div>
    </form>
    <a href="/shares">Share list</a>
</div>