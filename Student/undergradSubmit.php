<?php
	require_once("support.php");
	require_once('applicant.php');
	require_once('dblogin.php');
	// Starts session. We need to keep track to see if they are a valid user.
	session_start();

	// Basic form for entering applicant info. Makes a new applicant() object.
	$message = "";
	$body = <<<BODY
	<br>
	<img src="../Assets/umdLogo.gif" alt = "umdLogo.gif">
	<hr style="height:1px;border:none;color:#333;background-color:#333;" />
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
				<option>CMSC131</option>
				<option>CMSC132</option>
				<option>CMSC216</option>
				<option>CMSC250</option>
				<option>CMSC330</option>
				<option>CMSC351</option>
				<option>CMSC414</option>
				<option>CMSC420</option>
				<option>CMSC451</option>
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
				<option>CMSC131</option>
				<option>CMSC132</option>
				<option>CMSC216</option>
				<option>CMSC250</option>
				<option>CMSC330</option>
				<option>CMSC351</option>
				<option>CMSC414</option>
				<option>CMSC420</option>
				<option>CMSC451</option>
		</select><br>

		<b>Any other information you would like to provide us?</b>
		<input type="text" name="extraInformation" class="form-control" required><br/>

		</div>

		</div>

		</div>
		<div class="form-group container-fluid" align="center">
			<div class="col-sm-2 col-sm-push-2">
				<input type="submit" class="btn btn-info" name="continueButton" value="Continue">
			</div>
			<div class="col-sm-4 col-sm-push-4">
				<input type="submit" class="btn btn-info" name="mainMenuButton" value="Return to main menu">
			</div>
		</div>
		</form>
BODY;

	if(isset($_POST["continueButton"])){

		$first = trim($_POST["first"]); // String
		$last = trim($_POST["last"]); // String
		$email = trim($_POST["email"]); // String
		$directoryid = trim($_POST["directoryid"]); // String
		$gpa = trim($_POST["gpa"]); //Float
		$courses = serialize($_POST["courses"]); // String Array
		$previousCourses = serialize($_POST["previousCourses"]);
		$degree = "Undergraduate"; // UNDERGRADUATE SUBMISSION
		$transcript = addslashes(file_get_contents($_POST["transcript"])); // Blob
		$positionType = $_POST["positionType"]; // String enum
		$wantTeach = $_POST["wantTeach"]; // Boolean
		$advisor = "NULL";// Not a grad student
		$currentTA = "NULL";// Not a grad Student
		$currentStep = "NULL";
		$currentCourses = "NULL";
		$currentInstructor = "NULL";
		$passedMEI = "NULL";
		$takingMEI = "NULL";
		$extraInformation = $_POST["extraInformation"];

			$dbhost = "dbinstance389.cqiva6sltzci.us-east-2.rds.amazonaws.com";
			$dbuser = "dbuser";
			$dbpassword = "dragon123";
			$database = "cmsc389n";

			// To determine which table to use
			$applicationsTable = "Applications_Spring_2018";
			$coursesTable = "Courses_Spring_2018";


			if (isset($_SESSION["coursesTable"])) {
					$coursesTable = $_SESSION["coursesTable"];
			}

			if (isset($_SESSION["applicationsTable"])) {
					$applicationsTable = $_SESSION["applicationsTable"];
			}
			//Logging into database
		    $db = connectToDB($dbhost, $dbuser, $dbpassword, $database);

			//Setting the query string
			$sqlQuery  = "insert INTO $applicationsTable (First,Last,Email,Directory_ID,GPA,Courses,Previous,Degree,Transcript,Position_Type,Extra_Information) values
			(\"$first\",\"$last\",\"$email\",\"$directoryid\",$gpa,".$courses.",".$previousCourses.",\"$degree\",\"$transcript\",\"$positionType\",\"$extraInformation\")";
			//Executing Query
			$result = mysqli_query($db, $sqlQuery);



/*

$db_connection = new mysqli($dbhost, $dbuser, $dbpassword, $database);
	if ($db_connection->connect_error) {
			die($db_connection->connect_error);
	}

$first = "first".$random;
$last = "last".$random;
$email = "test".$random."@email.com";
$id = "test".$random."id";
$gpa = rand(0, 40) / 10;
$coursesToTA = ['CMSC131', 'CMSC132', 'CMSC250', 'CMSC216', 'CMSC351'];
$previousArr = ['CMSC131', 'CMSC132'];
$previous = serialize($previousArr);
$courses = serialize($coursesToTA);
$degree = 'PhD';
$fileResume = "resume.pdf";
$fileData = addslashes(file_get_contents($fileResume));
$wanteach = true;
$advisor = "Jon";
$currTA = true;
$currStep = 1;
$currCourse = "CMSC132";
$currInstructor = "Nelson";
$passedMEI = true;
$takingUMEI = false;
$extraInfo = "I really want this position";
$posi = "Part";

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




*/






			// If SQL query did not fail
			if ($result) {
				$body = <<<EOBODY
					<form action="{$_SERVER["PHP_SELF"]}" method="post">

					<h3>--Confirmation--</h3>

					<b>Name: </b> $first $last<br>
					<b>DirectoryID: </b> $directoryid<br>
					<b>Email: </b> $email<br>
					<b>Course Applied: </b> $courses<br>
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
			mysqli_close($db);
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
