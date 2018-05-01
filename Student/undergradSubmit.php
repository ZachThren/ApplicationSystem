<?php
	require_once("support.php");
	require_once('applicant.php');
	require_once('dblogin.php');
	// Starts session. We need to keep track to see if they are a valid user.
	session_start();

	// Basic form for entering applicant info. Makes a new applicant() object.
	$message = "";
	$body = <<<BODY
	<h1 align="center">Undergraduate UMD CS TA Application</h1>
	  <div class="container-fluid">

		<h3> Contact Information </h3>
		<form action="{$_SERVER["PHP_SELF"]}" method="post">

		<label>First Name: </label>
			<input type="text" name="first" placeholder="John" class="form-control" required><br>
		<label>Last Name: </label>
			<input type="text" name="last" placeholder="Smith" class="form-control" required><br>
		<b>Email: </b>
			<input type="email" name="email" placeholder="example@umd.edu" class="form-control" required><br>
		<div>
		<h3> Student Information </h3>
		<b>University Directory ID: </b>
			<input type="text" name="directoryid" placeholder="terps" class="form-control" required><br>
		<b>GPA: </b>
			<input type="number" name="gpa" step="0.01" placeholder="3.0" class="form-control" required><br>

		<div class="col-sm-6">
		<b>Courses applying to be a TA for: <br>(Ctrl/Cmd + Click for multiple)</b>
		<select id="course" class="form-control" name="courses[]" multiple size="10">
BODY;
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
mysqli_close($db_connection);
	$body .= <<<WHATEVER
		</select><br>
		</div>

		<br>
		<div class="form-group col-sm-6">
						<br/><br>
						<label for="transcript_upload">Please upload your unofficial transcript</label>
						<input type="file" name="transcript">
						<br/>
						<b>Part-time or Full-time?</b>
						<!-- you can replace radio-inline with checkbox -->
						<input type="radio" name="positionType" id="checkbox" value="Part" class="radio-inline"> Part
						<input type="radio" name="positionType" id="checkbox" value="Full" class="radio-inline"> Full
						<br/><br/>
						<b>Would you like to teach?</b>
						<!-- you can replace radio-inline with checkbox -->
						<input type="radio" name="wantTeach" id="checkbox" value="true" class="radio-inline"> Yes
						<input type="radio" name="wantTeach" id="checkbox" value="false" class="radio-inline"> No
		</div>




		</div>

		</div> <!--- #container div --->

		<div class="container-fluid" id="container">
		<b>Have you ever been/are you currently a TA for a CMSC course?</b>
						<!-- you can replace radio-inline with checkbox -->
						<input type="radio" name="currentTA" id="add" value="true" class="radio-inline" required> Yes
						<input type="radio" name="currentTA" value="false" class="radio-inline"> No
		<br><br/>
		<b> If you answered "Yes", which courses have you been/currently being a TA for? <br>(Ctrl/Cmd + Click for multiple)
		<select id="course" class="form-control" name="previousCourses[]" multiple size="10">
WHATEVER;
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
mysqli_close($db_connection);

		$body .=<<<NEXT
		</select><br>

		<b>Any other information you would like to provide us?</b>
		<input type="text" name="extraInformation" class="form-control" required><br/>

		</div>

		</div>

		</div>
		<div class="form-group container-fluid mycontainer">
			<div class="col-sm-2 col-sm-push-2">
				<input type="submit" class="btn btn-info continueButton" name="continueButton" value="Continue">
			</div>
		</div>
		</form>
NEXT;

	if(isset($_POST["continueButton"])){
		$db_connection = new mysqli($dbhost, $dbuser, $dbpassword, $database);
			if ($db_connection->connect_error) {
					die($db_connection->connect_error);
			}

		$first = $_POST["first"];
		$last = $_POST["last"];
		$email = $_POST["email"];
		$id = $_POST["directoryid"];
		$gpa = $_POST["gpa"];
		$previous = serialize($_POST["previousCourses"]);
		$coursesToTA = $_POST["courses"];
		$courses = serialize($coursesToTA );
		$degree = 'Undergraduate';

		$filePath = $_POST["transcript"];
		$fileData = addslashes(file_get_contents($_POST["transcript"]));

		$wanteach = $_POST["wantTeach"];
		$advisor = "NULL";


		if(strcmp($_POST["currentTA"],"true") == 0){
				$currTA = true;
		} else {
				$currTA = false;
		}


		$currStep = "NULL"; // 1, 2, 3
		$currCourse = "NULL";
		$currInstructor = "NULL";
		$passedMEI = "NULL";
		$takingUMEI = "NULL";
		$extraInfo = $_POST["extraInformation"];
		$posi = $_POST["positionType"];

		$applicationsTable = "Applications_Spring_2018";
		$coursesTable = "Courses_Spring_2018";


		if (isset($_SESSION["coursesTable"])) {
				$coursesTable = $_SESSION["coursesTable"];
		}

		if (isset($_SESSION["applicationsTable"])) {
				$applicationsTable = $_SESSION["applicationsTable"];
		}

		$sqlQuery = "insert into $applicationsTable (First, Last, Email, Directory_ID, GPA, Courses, Degree, Transcript, Previous, Want_Teach, Advisor, Current_TA, Current_Step, Current_Course, Current_Instructor, Passed_MEI, Taking_UMEI, Extra_Information, Position_Type) values ";
		$sqlQuery .= "('{$first}', '{$last}', '{$email}', '{$id}', '{$gpa}', '{$courses}', '{$degree}', '{$fileData}', '{$previous}','{$wanteach}','{$advisor}', '{$currTA}', '{$currStep}', '{$currCourse}', '$currInstructor', '$passedMEI', '$takingUMEI', '{$extraInfo}', '{$posi}')";

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

				if (empty($applying_Undergraduate)) {
	    		$applying_Undergraduate = [];
			}
			if (empty($applying_Graduate)) {
				$applying_Graduate = [];
			}


				if ($degree == 'Undergraduate') {
					array_push($applying_Undergraduate, $id);
				} else {
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


/*






*/






			// If SQL query did not fail
			if ($result) {
				if(empty($coursesToTA)){
					$coursesToTA = [];
				}
				$result = implode(", ",$coursesToTA);
				$body = <<<EOBODY
					<form action="{$_SERVER["PHP_SELF"]}" method="post">

					<h3>--Confirmation--</h3>

					<b>Name: </b> $first $last<br>
					<b>DirectoryID: </b> $id<br>
					<b>Email: </b> $email<br>
					<b>Course Applied: </b> $result<br>
					<b>File:</b> $filePath <br>
					<br>

					<input type="submit" name="mainMenuButton" value="Return to main menu">
					<br>

					</form>
EOBODY;
				session_destroy();
		        unset($_SESSION["userID"]);

			} else {
				// SQL query failed
				$body = "Inserting records failed.".mysqli_error($db);
			}
			mysqli_close($db_connection);
		}


	if(isset($_POST["mainMenuButton"])){
  	 	header("Location: ../main.html");
	}

	$page = generatePage($body.$message, "Department of Computer Science TA Application");
	echo $page;

	function connectToDB($host, $user, $password, $database) {
		$db = mysqli_connect($host, $user, $password, $database);
		if (mysqli_connect_errno()) {
			echo "Connect failed.\n".mysqli_connect_error();
			exit();
		}
		return $db;
	}
?>
