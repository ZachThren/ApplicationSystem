<?php
    require_once "support.php";
    require_once "dblogin.php"; 

    //retrieving fields from form session
    session_start();

    /*
    $applicationsTable = $_SESSION["applicationsTable"];
    $coursesTable = $_SESSION["coursesTable"];
    $course = $_SESSION["course"];
    */

    $applicationsTable = "Applications_Spring_2018";
    $coursesTable = "Courses_Spring_2018";
    $course = "CMSC131";

    

    // connecting to database;
    $db_connection = new mysqli($dbhost, $dbuser, $dbpassword, $database);
    if ($db_connection->connect_error) {
        die($db_connection->connect_error);
    }

    //retrieving data from courses table
    $course_query = "select Course, Applying_Undergraduate, Applying_Graduate, Accepted_Undergraduate, Accepted_Graduate, Max_Undergraduate, Max_Graduate, Max_Total from {$coursesTable} where Course = '{$course}'";

    $applying_Undergraduate = [];
    $applying_Graduate = [];

    $accepted_Undergraduate = [];
    $accepted_Graduate = [];

    $result1 = $db_connection->query($course_query);
    if (!$result1) {
        die("Retrieval of courses failed: ". $db_connection->error);
    } else {
        $num_rows = $result1->num_rows;
        if ($num_rows === 0) {
            echo "Empty Table<br>";
        } else {
            $result1->data_seek(0);
            $row = $result1->fetch_array(MYSQLI_ASSOC);
            $applying_Undergraduate = unserialize($row["Applying_Undergraduate"]);
            $applying_Graduate = unserialize($row["Applying_Graduate"]);

            $accepted_Undergraduate = unserialize($row["Accepted_Undergraduate"]);
            $accepted_Graduate = unserialize($row["Accepted_Graduate"]);
        }
    }

    $applications_query = "select Directory_ID, GPA from {$applicationsTable} order by GPA";  

    $result2 = $db_connection->query($applications_query);
    if (!$result2) {
        die("Retrieval failed: ". $db_connection->error);
    } else {
        $num_rows = $result2->num_rows;

        if ($num_rows === 0) {
            echo "No Applications<br>";
        } else {
            for ($row_index = 0; $row_index < $num_rows; $row_index++) {
                $result2->data_seek($row_index);

                
                $student = $result2->fetch_array(MYSQLI_ASSOC);






            }
        }
    }

    
    echo "success"
?>