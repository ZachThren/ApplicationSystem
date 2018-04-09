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
        <link href="../Assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script>
        $(document).ready(function(e){
          // Variables


          // Add rows to the form
          $("#add").click(function(e){
            $("#container").append("<p>Hello World!</p>");
          });

          // Add cols to the form

});

        </script>
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
