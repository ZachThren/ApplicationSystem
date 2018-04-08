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
            <h2> Update max TAs per course</h2><br>
            
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
        $sqlQuery = sprintf("update $table1 set Max_Total = %d where Course = %s", $_POST["Max"], $_POST["course"]);
        mysqli_query($db, $sqlQuery);
        $body .= "<br><br><p style='color: green'>Max TAs for".$_POST["course"]." chnaged to </p>".$_POST["Max"];
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