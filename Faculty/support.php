<?php
    function generatePage($body, $title="Example") {
        $page = <<<EOPAGE
        <!doctype html>
        <html>
            <head>
                <meta charset="utf-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <title>$title</title>
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
                <link href="Assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
                <link rel="apple-touch-icon" sizes="180x180" href="Assets/apple-touch-icon.png">
            <link rel="icon" type="image/png" sizes="32x32" href="Assets/favicon-32x32.png">
            <link rel="icon" type="image/png" sizes="16x16" href="Assets/favicon-16x16.png">
            <link rel="manifest" href="Assets/site.webmanifest">
            <link rel="mask-icon" href="Assets/safari-pinned-tab.svg" color="#5bbad5">
            <link rel="shortcut icon" href="Assets/favicon.ico">
            <meta name="msapplication-TileColor" content="#da532c">
            <meta name="msapplication-config" content="Assets/browserconfig.xml">
            <meta name="theme-color" content="#ffffff">
            </head>

            <body>
                <a href="../main.html"><img src="../Assets/umdLogo.gif" alt="UMD logo" style="margin-left: 10px; margin-top:15px;"></a><img src="../Assets/Logo.png" alt="TAMS logo" style="margin-right: 15px; margin-top:3px" align="right" height="60px" width="150px"><br>
                <hr style="height:1px;border:none;color:#C0C0C0;background-color:#C0C0C0;" />


                <div class="container-fluid">
                    $body
                </div>

                <br><hr style="height:1px; color:#C0C0C0; background-color:#C0C0C0;"/>

                <form action = "../main.html" method='post' align="center">
                        <input type="submit" class="btn btn-info" name="home" value="Return Home">
                </form>
                <br><br>
                <script src="https://code.jquery.com/jquery-3.1.1.min.js">

                <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
                </body>
        </html>
EOPAGE;
        return $page;
    }
?>
