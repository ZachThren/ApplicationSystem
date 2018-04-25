<?php
require_once("support.php");
require_once("courses.php");

session_start();
 
    $body = <<<EOBODY
        <form action="{$_SERVER["PHP_SELF"]}" method="post" class="container-fluid">
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
        </div><br><br>
        
        <input type="submit" class="btn btn-primary" name="displayApp" value="Display Applications"/>
        <br>
        </form>
eobody;


    if  (isset($_POST["displayApp"])) {
        $_SESSION["course"] = $_POST["course"];
        $_SESSION["sortby"] = $_POST["sortby"];

        if (isset($_POST["undergrad"])) {
            $_SESSION["undergraduate"] = true;
        } else {
            $_SESSION["undergraduate"] = false;
        }


        if (isset($_POST["graduate"])) {
            $_SESSION["graduate"] = true;
        } else {
            $_SESSION["graduate"] = false;
        }

        header("Location: adminDisplay.php");
    }

echo generatePage($body, "TA Application | Administrative Access");
?>