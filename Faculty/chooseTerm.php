<?php
    require_once("support.php");
    require_once("courses.php");
    require_once "dblogin.php";

    session_start();

    if (isset($_POST['termSubmit'])) {
        $term = $_POST['term'];
        $_SESSION["coursesTable"] = "Courses_".$term;
        $_SESSION["applicationsTable"] = "Applications_".$term;
        echo  $_SESSION["coursesTable"]."<br>".$_SESSION["applicationsTable"];
        header("Location: faculty.php");
    }

    $body = <<<EOBODY
        <form action="{$_SERVER["PHP_SELF"]}" method="post" class="container-fluid mycontainer">
        <h1>Select Term</h1><br>
        
        <div class="form-group">
            <label for="name">Select Course</label>
            <select class="form-control" name="term">                               
EOBODY;
    
    $db_connection = new mysqli($dbhost, $dbuser, $dbpassword, $database);
    if ($db_connection->connect_error) {
        die($db_connection->connect_error);
    }

    $query = "select Season, Year from Semesters order by 'ID' ASC";
    $result0 = mysqli_query($db_connection, $query);
    if (!$result0) {
        die("Retrieval of courses failed: ". $db_connection->error);
    } else {
        $num_rows_course = $result0->num_rows;
        if ($num_rows_course === 0) {
            echo "Empty Table<br>";
        } else {
            //iterating through the semesters
            for ($course_index = $num_rows_course - 1; $course_index >= 0; $course_index--) {
                $result0->data_seek($course_index);
                $sem = $result0->fetch_array(MYSQLI_ASSOC);
                $sem = $sem['Season']."_".$sem['Year'];
                $body .= "<option value="."$sem".">"."$sem"."</option> ";
            }

        }
    }
    $formPart2 = <<<part
        </select></div><br>
        <input type="submit" class="btn btn-info continueButton" name="termSubmit" value="Continue"/>
        <br>
        </form>
part;

    $finalBody = $body.$formPart2;
    echo generatePage($finalBody, "Choosing Term");
?>