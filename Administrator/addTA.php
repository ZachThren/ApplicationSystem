<?php
    require_once "support.php";
    require_once "dblogin.php"; 

    session_start();
    $course = $_SESSION["course"];
    $applicationsTable = "Applications_Spring_2018";
    $coursesTable = "Courses_Spring_2018";


    // connecting to database;
    $db_connection = new mysqli($dbhost, $dbuser, $dbpassword, $database);
    if ($db_connection->connect_error) {
        die($db_connection->connect_error);
    }

    ////////////////////////
    $course_query = "select Course, Applying_Undergraduate, Applying_Graduate, Accepted_Undergraduate, Accepted_Graduate, Max_Undergraduate, Max_Graduate, Max_Total from {$coursesTable} where Course = '{$course}'";

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

            $applying_Undergraduate = unserialize($a_course["Applying_Undergraduate"]);
            $applying_Graduate = unserialize($a_course["Applying_Graduate"]);

            $accepted_Undergraduate = unserialize($a_course["Accepted_Undergraduate"]);
            $accepted_Graduate = unserialize($a_course["Accepted_Graduate"]);
        }
    }
    ////////////////////////

    if (empty($applying_Undergraduate)) {
    	$applying_Undergraduate = [];
    }
    if (empty($applying_Graduate)) {
    	$applying_Graduate = [];
    }

    if (empty($accepted_Undergraduate)) {
        $accepted_Undergraduate = [];
    }
    if (empty($accepted_Graduate)) {
        $accepted_Graduate = [];
    }

    $key = $_POST["student"];
    
    $applications_query = "select Directory_ID, Degree from {$applicationsTable} where Directory_ID='{$key}'";  
    $student;
    
    $result2 = $db_connection->query($applications_query);
    if (!$result2) {
        die("Retrieval failed: ". $db_connection->error);
    } else {
        $num_rows = $result2->num_rows;

        if ($num_rows === 0) {
            echo "No Applications<br>";
        } else {
                $result2->data_seek(0);
                $student = $result2->fetch_array(MYSQLI_ASSOC);

                if ($student["Degree"] == "Undergraduate") {
                    array_push($accepted_Undergraduate, $key);
                } else {
                    array_push($accepted_Graduate, $key);
                }
        }
    }

    $updated_applying_undergraduate = [];
    foreach($applying_Undergraduate as $key => $value) {
    	if (!(in_array($value, $accepted_Undergraduate))) {
    		array_push($updated_applying_undergraduate, $value);
    	}
    }

    $updated_applying_graduate = [];
    foreach($applying_Graduate as $key => $value) {
        if (!(in_array($value, $accepted_Graduate))) {
            array_push($updated_applying_graduate, $value);
        }
    }

    $final_accepted_undergraduate = serialize($accepted_Undergraduate);
    $final_accepted_graduate = serialize($accepted_Graduate);

    $final_applying_undergraduate = serialize($updated_applying_undergraduate);
    $final_applying_graduate = serialize($updated_applying_graduate);

    
    $update_query = "update {$coursesTable} ";
    $update_query .= "set Applying_Undergraduate = '".$final_applying_undergraduate."', Applying_Graduate = '".$final_applying_graduate."', Accepted_Undergraduate = '".$final_accepted_undergraduate."', Accepted_Graduate = '".$final_accepted_graduate."' ";
    $update_query .= "where Course = '{$course}'";


    $result = $db_connection->query($update_query);
    if (!$result) {
        die("Retrieval of courses failed: ". $db_connection->error);
    } else {
        
    	header("Location: adminDisplay.php");
    }

?>