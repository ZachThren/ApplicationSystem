<?php
/**
 * Created by PhpStorm.
 * User: Azaah
 * Date: 4/8/2018
 * Time: 7:52 AM
 */
    require_once("support.php");
    require_once("courses.php");
    require_once("dblogin.php");
    session_start();

    $body = <<<EOBODY
        <form action="{$_SERVER["PHP_SELF"]}" method="post" class="container-fluid">
            <br><img src="umdLogo.gif" alt="UMD logo"/><br>
            <hr style="height:1px;border:none;color:#333;background-color:#333;" />
            <h2> Update number of TAs needed for a course</h2><br>
            
            <div class="form-group">
            <label for="name">Select Course</label>
            <select class="form-control" name="course">                               
EOBODY;

    foreach ($courses as $crse) {
        $body .= "<option value="."$crse".">"."$crse"."</option> ";
    }

    $body .= <<<EOBODY
        </select></div>
        <label for="name">Max Value</label>
        <input type="number" name="Max" style="background-color: lavender" class="form-control"/><br>
        <input type="submit" class="btn btn-primary" name="Edit" value="Update"/>
EOBODY;

    if  (isset($_POST["Edit"])) {
        $table1 = "Courses_Spring_2018";
        $db = connectToDB($dbhost, $dbuser, $dbpassword, $database);
        $sqlQuery = sprintf("UPDATE $table1 SET Max_Total = %d WHERE Course = %s", $_POST["Edit"], $_POST["Max"]);
        mysqli_query($db, $sqlQuery);
        $body .= "<br><br><b style='color: green'>Max TAs for ".$_POST["course"]." changed to </b>".$_POST["Max"];
        $body .= "
        <br><hr style=\"height:1px;border:none;color:#333;background-color:#333;\"/>
            <div style=\"text-align:left\"> If you have any question about our program, please contact the system administrator at
                <a style=\"text-align:center\" href=\"mailto:your address\">admin@terpmail.edu.umd</a>
            </div>
        </form>";
    }

    echo generatePage($body, "Add Semester");

    function connectToDB($host, $user, $password, $database) {
        $db = mysqli_connect($host, $user, $password, $database);
        if (mysqli_connect_errno()) {
            echo "Connect failed.\n".mysqli_connect_error();
            exit();
        }
        return $db;
    }
?>