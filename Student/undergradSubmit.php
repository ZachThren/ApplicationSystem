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
		<select id="course" class="form-control" name="courses" multiple size="10">
				<option>CMSC 131</option>
				<option>CMSC 132</option>
				<option>CMSC 216</option>
				<option>CMSC 250</option>
				<option>CMSC 330</option>
				<option>CMSC 351</option>
				<option>CMSC 414</option>
				<option>CMSC 420</option>
				<option>CMSC 451</option>
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
		<select id="course" class="form-control" name="previousCourses" multiple size="10">
				<option>CMSC 131</option>
				<option>CMSC 132</option>
				<option>CMSC 216</option>
				<option>CMSC 250</option>
				<option>CMSC 330</option>
				<option>CMSC 351</option>
				<option>CMSC 414</option>
				<option>CMSC 420</option>
				<option>CMSC 451</option>
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
		$courses = $_POST["courses"]; // String Array
		$previousCourses = $_POST["previousCourses"];
		$degree = "Undergraduate"; // UNDERGRADUATE SUBMISSION
		$transcript = "NULL"; // Blob
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
			$applicationsTable = "Applications_Spring_2018";
			$coursesTable = "Courses_Spring_2018";
			//Logging into database
		    $db = connectToDB($dbhost, $dbuser, $dbpassword, $database);

			//Setting the query string
			$sqlQuery  = "insert INTO $applicationsTable (First,Last,Email,Directory_ID,GPA,Courses,Previous,Degree,Transcript,Position_Type,Extra_Information) values
			(\"$first\",\"$last\",\"$email\",\"$directoryid\",$gpa,\"$courses\",\"$previousCourses\",\"$degree\",$transcript,\"$positionType\",\"$extraInformation\")";
			//Executing Query
			$result = mysqli_query($db, $sqlQuery);

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
