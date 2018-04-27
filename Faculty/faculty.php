<?php
    require_once("support.php");
    require_once("courses.php");
    require_once "dblogin.php";

    session_start();

    $applicationsTable = "Applications_Spring_2018";
    $coursesTable = "Courses_Spring_2018";

    if (isset($_POST["coursesTable"])) {
        $coursesTable = $_POST["coursesTable"];
    }
    if (isset($_POST["applicationsTable"])) {
        $applicationsTable = $_POST["applicationsTable"];
    }

    $_SESSION["coursesTable"] = $coursesTable;
    $_SESSION["applicationsTable"] = $applicationsTable;

    $body = <<<EOBODY
        <form action="{$_SERVER["PHP_SELF"]}" method="post" class="container-fluid">
        <h1>Applications</h1><br>
        
        <div class="form-group">
            <label for="name">Select Course</label>
            <select class="form-control" name="course">                               
EOBODY;
    
    $db_connection = new mysqli($dbhost, $dbuser, $dbpassword, $database);
    if ($db_connection->connect_error) {
        die($db_connection->connect_error);
    }
    $course_query = "select Course, Applying_Undergraduate, Applying_Graduate, Accepted_Undergraduate, Accepted_Graduate, Max_Undergraduate, Max_Graduate, Max_Total from {$coursesTable} order by 'Course'";
    $result0 = mysqli_query($db_connection, $course_query);
    if (!$result0) {
        die("Retrieval of courses failed: ". $db_connection->error);
    } else {
        $num_rows_course = $result0->num_rows;
        if ($num_rows_course === 0) {
            echo "Empty Table<br>";
        } else {
            //iterating through the courses
            for ($course_index = 0; $course_index < $num_rows_course; $course_index++) {
                $result0->data_seek($course_index);
                $a_course = $result0->fetch_array(MYSQLI_ASSOC);
                $currName = $a_course['Course'];
                $body .= "<option value="."$currName".">"."$currName"."</option> ";
            }

        }
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
        
        <input type="submit" class="btn btn-info" name="displayApp" value="Display Applications"/>
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

        header("Location: facultyDisplay.php");
    }

echo generatePage($body, "TA Application | Administrative Access");
?>