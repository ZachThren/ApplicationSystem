<?php
require_once("support.php");
require_once("courses.php");
session_start();

    $body = <<<EOBODY
        <form action="{$_SERVER["PHP_SELF"]}" method="post" class="container-fluid">
        <br><img src="umdLogo.gif" alt="UMD logo"/><br>
        <hr style="height:1px;border:none;color:#333;background-color:#333;" />
        <h1>Applications</h1><br>
        
        <div class="form-group">
            <label for="name">Select Course</label>
            <select class="form-control" name="course">                               
EOBODY;
    
    foreach ($courses as $course) {
        $body .= "<option value="."$course".">"."$course"."</option> ";
    }

    $body .= <<<eobody
        </select></div><br>

        <div class="form-group">
            <label for="sortby">Select field to sort by</label>
            <select class="form-control" name="sortby">
                <option value="Directory_ID">Directory ID</option>
                <option value="First">First Name</option>
                <option value="Last">Last Name</option>
                <option value="Email">Email</option>
                <option value="GPA">GPA</option>
            </select>
        </div><br>

        <div class="form-check form-check-inline">
          <input class="form-check-input" type="checkbox" value="Gradute" name="graduate">
          <label class="form-check-label" for="gradute">Gradute</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="checkbox" value="Undergraduate" name="undergrad">
          <label class="form-check-label" for="undergrad">Undergraduate</label>
        </div><br>
        
        <input type="submit" class="btn btn-primary" name="displayApp" value="Display Applications"/>
        <br><br><hr style="height:1px;border:none;color:#333;background-color:#333;"/>
        <div style="text-align:left"> If you have any question about our program, please contact the system administrator at
            <a style="text-align:center" href="mailto:your address">admin@terpmail.edu.umd</a>
        </div>
        </form>
eobody;


    if  (isset($_POST["displayApp"])) {
        $_SESSION["course"] = $_POST["course"];
        $_SESSION["sortby"] = $_POST["sortby"];
        $_SESSION["undergraduate"] = $_POST["undergrad"];
        $_SESSION["graduate"] = $_POST["graduate"];

        header("Location: adminDisplay.php");
    }

echo generatePage($body, "TA Application | Administrative Access");
?>