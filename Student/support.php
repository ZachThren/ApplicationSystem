<?php

function generatePage($body, $title="Example") {
    $page = <<<EOPAGE
<!doctype html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>$title</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="student/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body>
            $body
            <script src="bootstrap/jquery-3.2.1.min.js"></script>
            <script src="bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>
EOPAGE;

    return $page;
}
?>
