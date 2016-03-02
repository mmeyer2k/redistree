<?php

use Mmeyer2k\RedisTree\RedisTreeModel;
?><!doctype html>
<html>
    <head>
        <title> @yield('title') </title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        <link rel="icon" type="image/png" href="//cdn.rawgit.com/mmeyer2k/redistree/master/assets/favicon.ico">
        <style>
            * {
                -webkit-border-radius: 0 !important;
                -moz-border-radius: 0 !important;
                border-radius: 0 !important;
            }
            div.container {
                padding: 0px 0px 0px 0px;
                box-shadow: 0px 0px 15px #222222;
            }
            .navbar, .panel, .jumbotron {
                margin-bottom: 0;
            }
            .monospace {
                font-family: monospace;
            }
            .glyphicon-refresh-animate {
                -animation: spin .7s infinite linear;
                -webkit-animation: spin2 .7s infinite linear;
            }
            @-webkit-keyframes spin2 {
                from { -webkit-transform: rotate(0deg);}
                to { -webkit-transform: rotate(360deg);}
            }
            @keyframes spin {
                from { transform: scale(1) rotate(0deg);}
                to { transform: scale(1) rotate(360deg);}
            }
        </style>
        @yield('head')
    </head>
    <body>
        <div class="container">
            {!! \view('redistree::navbar') !!}
            @yield('content')
        </div>
        @yield('tail')
        <script>
            function reloadPage() {
                window.location.href = '{!! $_SERVER["REQUEST_URI"] !!}';
            }

            function sendAjax(url, data) {
                dimControls();
                $.ajax({
                    url: url,
                    data: data,
                    method: 'POST',
                    success: function () {
                        reloadPage();
                    }
                });
            }

            function dimControls() {
                $('.btn, input, select, textarea').prop('disabled', true);
            }

            $(document).ready(function () {
                $('#btnRefresh').click(function () {
                    dimControls();
                    $('#btnRefreshIcon').addClass('glyphicon-refresh-animate');
                    window.location.href = '{{ $_SERVER["REQUEST_URI"] }}';
                });
                if ("{{ (int) RedisTreeModel::option('tooltips') }}" === "1") {
                    $('[data-toggle="tooltip"]').tooltip();
                } else {
                    $('[data-toggle="tooltip"]').removeAttr('title');
                }
            });
        </script>
    </body>
</html>