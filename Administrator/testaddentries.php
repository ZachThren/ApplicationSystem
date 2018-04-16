<?php
	require_once("support.php");	
    require_once "dblogin.php"; 

	$table = $coursesTable;

	$db_connection = new mysqli($dbhost, $dbuser, $dbpassword, $database);
    if ($db_connection->connect_error) {
        die($db_connection->connect_error);
    }
	
	
	$first = "test4";
	$email = "test4@email.com";
	$id = "test4id";
	$gpa = 3.0;
	$coursesToTA = ['cmsc131', 'cmsc132', 'cmsc250', 'cmsc216', 'cmsc351'];
	$courses = serialize($coursesToTA);
	$degree = 'Undergraduate';
	$fileResume = "resume.pdf";
	$fileData = addslashes(file_get_contents($fileResume));
	
	$sqlQuery = "insert into $applicationsTable (First, Email, Directory_ID, GPA, Courses, Degree, Transcript) values ";
	$sqlQuery .= "('{$first}', '{$email}', '{$id}', '{$gpa}', '{$courses}', '{$degree}', '{$fileData}')";

	$result1 = $db_connection->query($sqlQuery);

	if (!$result1) {
        die("Applications failed: ". $db_connection->error);
    }


	foreach($coursesToTA as $key => $value) {
		//retrieving data from courses table
	    $course_query = "select Course, Applying_Undergraduate, Applying_Graduate from {$coursesTable} where Course = '{$value}'";
	    $applying_Undergraduate = array();
	    $applying_Graduate = array();

	    
	    $result1 = $db_connection->query($course_query);
	    if (!$result1) {
	        die("Courses failed: ". $db_connection->error);
	    } else {
	        $num_rows = $result1->num_rows;
	        if ($num_rows === 0) {
	            echo "Empty Table<br>";
	        } else {
	            $result1->data_seek(0);
	            $row = $result1->fetch_array(MYSQLI_ASSOC);
	            $applying_Undergraduate = unserialize($row["Applying_Undergraduate"]);
	            $applying_Graduate = unserialize($row["Applying_Graduate"]);
	            
	        }
	    }
	    

	    if ($degree == 'Undergraduate') {
	    	global $applying_Undergraduate;
	    	array_push($applying_Undergraduate, $id);
	    } else {
	    	global $applying_Graduate;
	    	array_push($applying_Graduate, $id);
	    }

	    $Applying_U = serialize($applying_Undergraduate);
	    $Applying_G = serialize($applying_Graduate);

	    $update_query = "update Courses_Spring_2018 set Applying_Graduate = '{$Applying_G}', Applying_Undergraduate = '{$Applying_U}' where Course = '{$value}'";

	    $result = $db_connection->query($update_query);
	    if (!$result) {
	        die("Retrieval of courses failed: ". $db_connection->error);
	    } 

	}
	
	echo "added";
?>