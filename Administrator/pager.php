<?php

function generatePage($body, $title) {
    $page = <<<EOPAGE
        <!doctype html>
        <html class="container-fluid">
            <head> 
                <meta charset="utf-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <title> $title </title> 
                <link rel="stylesheet" href="myStyles.css" type="text/css" />
                <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
            </head>
                    
            <body class="container-fluid" style="font-family: 'Courier New'">
                $body
                <script src="bootstrap/jquery-3.2.1.min.js"></script>
                <script src="bootstrap/js/bootstrap.min.js"></script>
            </body>
        </html>
EOPAGE;

        return $page;
    }
?>