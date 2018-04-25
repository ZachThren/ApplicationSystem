<?php
	require_once("support.php");
	require_once('applicant.php');

	// Starts session. We need to keep track to see if they are a valid user.
	session_start();

	// Basic form for entering applicant info. Makes a new applicant() object.
	$message = "";
	$body = <<<BODY
	<br>	
	<h1 align="center">UMD CS TA Application</h1>
	<h3> Are you an Undergraduate or Graduate student? </h3>
	<form action="{$_SERVER["PHP_SELF"]}" method="post">
	<div class="form-group">
			<div class="col-sm-3 col-sm-push-1">
					<input type="submit" class="btn btn-info" value="Undergraduate" name="undergraduate">
			</div>
			<div class="col-sm-3 col-sm-push-1">
					<input type="submit" class="btn btn-info" value="Graduate" name="graduate">
			</div>
			<div class="col-sm-3 col-sm-push-1">
					<input type="submit" class="btn btn-info" value="Main Menu" name="main">
			</div>
	</div>

	</form>
	<img></img>
BODY;

	if(isset($_POST["undergraduate"])){
		header("Location: undergradSubmit.php");
	}

	if(isset($_POST["graduate"])){
  	 	header("Location: graduateSubmit.php");
	}

	if(isset($_POST["main"])){
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
