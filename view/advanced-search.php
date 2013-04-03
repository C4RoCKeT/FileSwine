<script type="text/javascript">
    $(function() {
        $('#search').typeahead({source: <?php echo json_encode($searches); ?>});
    });
</script>
<div id="search-div">
    <form action="/search" method="get">
        <div class="input-append">
            <input type="search" autofocus="autofocus" id="search" name="search" data-provide="typeahead"/>
            <button id="search-button" class="btn btn-primary" type="submit">Search</button>
        </div>
        <label for="extension">Search for</label>
        <select id="extension" name="extension">
            <option value="">I don't give a shit</option>
            <option value="folders">Folders</option>
            <option value="video">Video</option>
            <option value="audio">Audio</option>
            <option value="images">Images</option>
            <option value="documents">Documents</option>
        </select>
        <select id="operand" name="operand">
            <option value="greater">></option>
            <option value="greaterorequal">>=</option>
            <option value="smaller"><</option>
            <option value="smallerorequal"><=</option>
        </select>
        <input type="number" id="file-size" name="file_size" /> MB
    </form>
</div>