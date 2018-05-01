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
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="../Assets/mainstyle.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script>
        $(document).ready(function(e){
          // Variables


          // Add rows to the form
          $("#radioButton").click(function(e){
            $("#container").append("	<b> If you answered "Yes", which courses have you been/currently being a TA for? <br>(Ctrl/Cmd + Click for multiple)
          		<select id="course" class="form-control" multiple size="10">
          				<option>CMSC 131</option>
          				<option>CMSC 132</option>
          				<option>CMSC 216</option>
          				<option>CMSC 250</option>
          				<option>CMSC 330</option>
          				<option>CMSC 351</option>
          				<option>CMSC 414</option>
          				<option>CMSC 420</option>
          				<option>CMSC 451</option>
          		</select><br>");
          });

          // Add cols to the form

});

        </script>
    </head>

    <body>
        <img src="../Assets/umdLogo.gif" alt="UMD logo" style="margin-left: 10px; margin-top:15px;"><img src="../Assets/Logo.png" alt="TAMS logo" style="margin-right: 15px; margin-top:3px" align="right" height="60px" width="150px"><br>
        <hr style="height:1px;border:none;color:#C0C0C0;background-color:#C0C0C0;" />

        <div class="container-fluid">
          $body
        </div>
        
        <br><hr style="height:1px;border:none;color:#C0C0C0;background-color:#C0C0C0;"/>
        <form action = "../main.html" method='post' align="center">
                <input type="submit" class="btn btn-info" name="home" value="Return Home">
        </form>
        <br><br>

        <script src="bootstrap/jquery-3.2.1.min.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>
EOPAGE;

    return $page;
}
?>
