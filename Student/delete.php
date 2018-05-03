<?php
	require_once("support.php");
	require_once('applicant.php');
	require_once('dblogin.php');

  session_start();
  $currentStudent = $_SESSION["studentDirectoryId"];
  $currentStudentPassword = $_SESSION["studentPassword"];
  //echo "<p>{$currentStudent}</p>";

  	$message = "";
    $body = <<<BODY
    <h1 align="left"> Deleting application </h1>
    <h6>Are you sure you want to delete the TA application for the current user?</h6>

    <div class="container-fluid">

    <br><br>
    <span> Directory ID:
      <strong>
        <span class="text-success">{$currentStudent}</span>
      </strong>
    </span>
    <br><br><br><br>
    <div align="center">
    <form action="{$_SERVER["PHP_SELF"]}" method="POST">
    <input type="submit" class="btn btn-danger" name="deleteBTN" value="Yes, please delete my application">
    </form>
    </div>
    </div>




BODY;

  if(isset($_POST["deleteBTN"])){
    $applicationsTable = "Applications_Spring_2018";
    $coursesTable = "Courses_Spring_2018";

    if (isset($_SESSION["coursesTable"])) {
        $coursesTable = $_SESSION["coursesTable"];
    }

    if (isset($_SESSION["applicationsTable"])) {
        $applicationsTable = $_SESSION["applicationsTable"];
    }

    $db_connection = new mysqli($dbhost, $dbuser, $dbpassword, $database);
			if ($db_connection->connect_error) {
					die($db_connection->connect_error);
			}
    $sqlQuery = "delete from {$applicationsTable} where Directory_ID=\"{$currentStudent}\"";

    $result = $db_connection->query($sqlQuery);

    if(!$result){
      die("Deletion of TA record failed: ". $db_connection->error);
    } else {
      $body = <<<EOBODY
      <form action="{$_SERVER["PHP_SELF"]}" method="post">

      <h3>--Delete confirmation--</h3>

      <span class="text-success">Record deleted for</span>
      <b> $currentStudent</b><br>
      <br>
      <input type="submit" name="reapplyBTN" value="Re-Apply to be a TA" class="btn btn-secondary">

      </form>

EOBODY;
    }
    mysqli_close($db_connection);



  }
  if(isset($_POST["reapplyBTN"])){
    header("Location: ./CASStudent.php");
  }

$page = generatePage($body.$message, "Department of Computer Science TA Application");
echo $page;
?>
