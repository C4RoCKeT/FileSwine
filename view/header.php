<?php
$_GET['search'] = !empty($_GET['search']) ? trim($_GET['search']) : '';
$_GET['extension'] = !empty($_GET['extension']) ? trim($_GET['extension']) : ''
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <link href="/files/css/bootstrap.css" rel="stylesheet">
        <link href="/files/css/style.css" rel="stylesheet">
        <link href="/files/css/cus-icons.css" rel="stylesheet">
        <link href="/files/css/jquery-ui-1.9.2.custom.min.css" rel="stylesheet">
        <link href="/files/css/jquery.gridster.min.css" rel="stylesheet">
        <script src="//code.jquery.com/jquery-latest.js"></script>
        <script src="/files/js/bootstrap.js"></script>
        <title>
            FileSwine - LAN Indexer
        </title>
    </head>
    <body>
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="brand" href="/"><img style="margin-right:10px;" src="/files/img/8bit.png" alt="That's my file!" />FileSwine</a>
                    <div style="padding:13px 20px 12px;float:right;text-align:center;">
                        FileSwine Protocol Handler<br/>
                        <a href="/files/FileSwine_Protocol_Handler.zip">
                            <img src='/files/img/download.png' alt="Download FileSwine Protocol Handler" />
                        </a>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
        <div class="container">

