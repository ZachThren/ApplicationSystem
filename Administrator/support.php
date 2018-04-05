<?php
    function generatePage($body, $title="Example") {
        $page = <<<EOPAGE
        <!doctype html>
        <html>
            <head> 
                <meta charset="utf-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <title>$title</title>
                <link rel="stylesheet" type="text/css" href="../Assets/bootstrap/css/bootstrap.css">
                <link rel="stylesheet" type="text/css" href="style.css">
            </head>

            <body>
                <div class="container-fluid">
                    $body
                </div>
            </body>
        </html>
EOPAGE;
        return $page;
    }
?>