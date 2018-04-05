<?php
    require_once "support.php";
    require_once "dblogin.php"; 

    session_start();
    $course = $_SESSION["course"];
    $term = $_SESSION["term"];





    // connecting to database;
    $db_connection = new mysqli($dbhost, $dbuser, $dbpassword, $database);
    if ($db_connection->connect_error) {
        die($db_connection->connect_error);
    }

    $course_query = "select Course, Applying_For_{$term}, Accepted_For_{$term} from Courses where Course = '{$course}'";
    $applying_TAs = [];
    $accepted_TAs = [];

    $field_applying_term = "Applying_For_".$term;
    $field_accepted_term = "Accepted_For_".$term;

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

            $applying_TAs = unserialize($row[$field_applying_term]);
            $accepted_TAs = unserialize($row[$field_accepted_term ]);
        }
    }

    if (empty($accepted_TAs)) {
    	$accepted_TAs = [];
    }
    if (empty($applying_TAs)) {
    	$applying_TAs = [];
    }

    foreach($_POST as $key => $value) {
    	if ($key != "Add") {
    		array_push($accepted_TAs, $key);
    	}
    }

    $updated_applying_TAs = [];
    foreach($applying_TAs as $key => $value) {
    	if (!(in_array($value, $accepted_TAs))) {
    		array_push($updated_applying_TAs, $value);
    	}
    }

    $final_accepted = serialize($accepted_TAs);
    $final_applying = serialize($updated_applying_TAs);


    $update_query = "update Courses ";
    $update_query .= "set {$field_applying_term} = '".$final_applying."', {$field_accepted_term} = '".$final_accepted."' ";
    $update_query .= "where Course = '{$course}'";

    $result = $db_connection->query($update_query);
    if (!$result) {
        die("Retrieval of courses failed: ". $db_connection->error);
    } else {
    	header("Location: adminDisplay.php");
    }

?>