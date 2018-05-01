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
	<h3 align="Center"> Are you an Undergraduate or Graduate student? </h3>
	<form action="{$_SERVER["PHP_SELF"]}" method="post">
	<div class="form-group" align="center">
		<div class="row slideanim">
		  <div class="col-sm-2 col-xs-12">
          </div>
          <div class="col-sm-4 col-xs-12">
            <div class="panel panel-default text-center">
              <div class="panel-heading">
                <h1>Undergraduate</h1>
              </div>
              <div class="panel-body">
                <p>click below</p>
              </div>
              <div class="panel-footer">
                <input type="submit" class="btn btn-info" value="Apply" name="undergraduate">
              </div>
            </div>
          </div>
          <div class="col-sm-4 col-xs-12">
            <div class="panel panel-default text-center">
              <div class="panel-heading">
                <h1>Graduate</h1>
              </div>
              <div class="panel-body">
                <p>click below</p>
              </div>
              <div class="panel-footer">
                <input type="submit" class="btn btn-info" value="Apply" name="graduate">
              </div>
            </div>
          </div>
          <div class="col-sm-2 col-xs-12">
          </div>
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
