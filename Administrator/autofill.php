<?php
    require_once "support.php";
    require_once "dblogin.php";
    require_once "courses.php";

    //retrieving fields from form session
    session_start();

    /*
    $applicationsTable = $_SESSION["applicationsTable"];
    $coursesTable = $_SESSION["coursesTable"];
    $course = $_SESSION["course"];
    */

    $applicationsTable = "Applications_Spring_2018";
    $coursesTable = "Courses_Spring_2018";
    

    // connecting to database;
    $db_connection = connectToDB($dbhost, $dbuser, $dbpassword, $database);
    if ($db_connection->connect_error) {
        die($db_connection->connect_error);
    }

    //retrieving data from courses table
    foreach ($courses as $every_course) {
        $course_query = "select Course, Applying_Undergraduate, Applying_Graduate, Accepted_Undergraduate, Accepted_Graduate, Max_Undergraduate, Max_Graduate, Max_Total from {$coursesTable} where Course = '{$every_course}'";

        $applying_Undergraduate = [];
        $applying_Graduate = [];

        $accepted_Undergraduate = [];
        $accepted_Graduate = [];

        $result1 = mysqli_query($db_connection, $course_query);
        if (!$result1) {
            die("Retrieval of courses failed: ". $db_connection->error);
        } else {
            $num_rows = $result1->num_rows;
            if ($num_rows === 0) {
                echo "Empty Table<br>";
            } else {
                $a_course = mysqli_fetch_array($result1, MYSQLI_ASSOC);
                $new_Max_Ugrad = $a_course["Max_Udergraduate"];;
                $new_Max_Grad = $a_course["Max_Graduate"];

                $applying_Undergraduate = unserialize($a_course["Applying_Undergraduate"]);
                $applying_Graduate = unserialize($a_course["Applying_Graduate"]);

                $accepted_Undergraduate = unserialize($a_course["Accepted_Undergraduate"]);
                $accepted_Graduate = unserialize($a_course["Accepted_Graduate"]);
            }
        }

        $applications_query = "select Directory_ID, GPA from {$applicationsTable} order by GPA";

        $result2 = mysqli_query($db_connection, $applications_query);
        if (!$result2) {
            die("Retrieval failed: ". $db_connection->error);
        } else {
            $num_rows = $result2->num_rows;

            if ($num_rows === 0) {
                echo "No Applications<br>";
            } else {
                while ($a_student = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
                    if ($a_student["GPA"] >= $gpa_cutoff && ($a_student["Degree"] === 'MS' ||  $a_student["Degree"] === 'PhD')) {
                        unset($applying_Graduate[$a_student["Directory_ID"]]);
                        array_push($accepted_Graduate, $a_student["Directory_ID"]);
                        $new_Max_Grad--;
                    } else if ($a_student["GPA"] >= $gpa_cutoff && ($a_student["Degree"] === 'Undergraduate')) {
                        unset($applying_Undergraduate[$a_student["Directory_ID"]]);
                        array_push($accepted_Undergraduate, $a_student["Directory_ID"]);
                        $new_Max_Ugrad--;
                    }
                }
            }

            $update_Applying_Ugrad = serialize($applying_Undergraduate);
            $update_Applying_Grad = serialize($applying_Graduate);
            $update_Accepted_Ugrad = serialize($accepted_Undergraduate);
            $update_Accepted_Grad = serialize($accepted_Graduate);
            $update_query = "insert into {$applicationsTable}, (Applying_Undergraduate, Applying_Graduate, Accepted_Undergraduate, Accepted_Graduate, Max_Undergraduate, Max_Graduate) values ($update_Applying_Ugrad, $update_Applying_Grad, $update_Accepted_Ugrad, $update_Accepted_Grad, $new_Max_Ugrad, $new_Max_Grad)";
        }
    }
//    $course_query = "select Course, Applying_Undergraduate, Applying_Graduate, Accepted_Undergraduate, Accepted_Graduate, Max_Undergraduate, Max_Graduate, Max_Total from {$coursesTable} where Course = '{$course}'";
//
//    $applying_Undergraduate = [];
//    $applying_Graduate = [];
//
//    $accepted_Undergraduate = [];
//    $accepted_Graduate = [];
//
//    $result1 = $db_connection->query($course_query);
//    if (!$result1) {
//        die("Retrieval of courses failed: ". $db_connection->error);
//    } else {
//        $num_rows = $result1->num_rows;
//        if ($num_rows === 0) {
//            echo "Empty Table<br>";
//        } else {
//            $result1->data_seek(0);
//            $row = $result1->fetch_array(MYSQLI_ASSOC);
//            $applying_Undergraduate = unserialize($row["Applying_Undergraduate"]);
//            $applying_Graduate = unserialize($row["Applying_Graduate"]);
//
//            $accepted_Undergraduate = unserialize($row["Accepted_Undergraduate"]);
//            $accepted_Graduate = unserialize($row["Accepted_Graduate"]);
//        }
//    }
//
//    $applications_query = "select Directory_ID, GPA from {$applicationsTable} order by GPA";
//
//    $result2 = $db_connection->query($applications_query);
//    if (!$result2) {
//        die("Retrieval failed: ". $db_connection->error);
//    } else {
//        $num_rows = $result2->num_rows;
//
//        if ($num_rows === 0) {
//            echo "No Applications<br>";
//        } else {
//            for ($row_index = 0; $row_index < $num_rows; $row_index++) {
//                $result2->data_seek($row_index);
//
//
//                $student = $result2->fetch_array(MYSQLI_ASSOC);
//
//
//
//
//
//
//            }
//        }
//    }

    
    echo "success";

    function connectToDB($host, $user, $password, $database) {
        $db = mysqli_connect($host, $user, $password, $database);
        if (mysqli_connect_errno()) {
            echo "Connect failed.\n".mysqli_connect_error();
            exit();
        }
        return $db;
    }
?>