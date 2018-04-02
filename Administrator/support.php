<?php
    function generatePage($body, $title="Example") {
        $page = <<<EOPAGE
        <!doctype html>
        <html>
            <head> 
                <meta charset="utf-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <title>$title</title>
                <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
                <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-theme.css">
                <link rel="stylesheet" type="text/css" href="style.css">
            </head>

            <body>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                        </div>
                        <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                            $body
                        </div>
                        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                        </div>
                    </div>
                </div>
            </body>
        </html>
EOPAGE;
        return $page;
    }
?>