<?php

function generatePage($body, $title) {
    $page = <<<EOPAGE
        <!doctype html>
        <html class="container-fluid">
            <head> 
                <meta charset="utf-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <title> $title </title> 
                <link rel="stylesheet" href="mainstyle.css" type="text/css" />
                <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
            </head>
                    
            <body class="container-fluid">
                $body
            </body>
        </html>
EOPAGE;

    return $page;
}
?>