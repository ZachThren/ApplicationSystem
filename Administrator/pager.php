<?php

function generatePage($body, $title) {
    $page = <<<EOPAGE
        <!doctype html>
        <html>
            <head> 
                <meta charset="UTF-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <title> $title </title> 
                <link href="mainstyle.css" rel="stylesheet" type="text/css" />
                <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
                <link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css">
            </head>
                    
            <body class="container-fluid">
                $body
                <script src="bootstrap/jquery-3.2.1.min.js"></script>
                <script src="bootstrap/js/bootstrap.min.js"></script>
            </body>
        </html>
EOPAGE;

        return $page;
    }
?>