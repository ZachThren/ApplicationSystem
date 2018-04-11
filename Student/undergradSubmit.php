<?php
	require_once("support.php");
	require_once('applicant.php');

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
		<select id="course" class="form-control" multiple size="10">
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
		$transcript = $_POST["transcript"]; // Blob
		$positionType = $_POST["positionType"]; // String enum
		$wantTeach = $_POST["wantTeach"]; // Boolean
		$currentTA = $_POST["currentTA"]; // Boolean

		if ($password !== $verifypass) {
			$message = "<h2>Passwords do not match</h2>";
		} else {
			$safePass = password_hash($password, PASSWORD_DEFAULT);
			$password = "";
			$userID = new applicant($name,$email,$gpa,$year,$gender,$safePass);
			$_SESSION["userID"] = serialize($userID);
		}

		if(isset($_SESSION['userID'])){
			$userID = unserialize($_SESSION["userID"]);

			$host = "localhost";
		    $user = "dbuser";
		    $dbpassword = "goodbyeWorld";
		    $database = "applicationdb";
		    $table = "applicants";
		    $db = connectToDB($host, $user, $dbpassword, $database);

		    $name = $userID->getName();
		    $email = $userID->getEmail();
		 	$gpa = $userID->getGpa();
			$year = $userID->getYear();
			$gender = $userID->getGender();
			$password = $userID->getPassword();

			$sqlQuery  = "insert into $table (name,email,gpa,year,gender,password) values (\"$name\",\"$email\",$gpa,$year,\"$gender\",\"$password\")";
			$result = mysqli_query($db, $sqlQuery);

			if ($result) {
				$body = <<<EOBODY
					<form action="{$_SERVER["PHP_SELF"]}" method="post">

					<h3>The following entry has been added to the database</h3>

					<b>Name: </b> $name<br>
					<b>Email: </b> $email<br>
					<b>Gpa: </b> $gpa<br>
					<b>Year: </b> $year<br>
					<b>Gender: </b> $gender<br>
					<br>

					<input type="submit" name="mainMenuButton" value="Return to main menu">
					<br>

					</form>
EOBODY;
				session_destroy();
		        unset($_SESSION["userID"]);

			} else {
				$body = "Inserting records failed.".mysqli_error($db);
			}
			mysqli_close($db);
		}
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
