<?php
require_once "support.php";
require_once "dblogin.php";

session_start();
  // connecting to database;
  $applicationsTable = "Applications_Spring_2018";
  $coursesTable = "Courses_Spring_2018";

  if (isset($_SESSION["coursesTable"])) {
      $coursesTable = $_SESSION["coursesTable"];
  }

  if (isset($_SESSION["applicationsTable"])) {
      $applicationsTable = $_SESSION["applicationsTable"];
  }

  $id = $_POST['transcript'];

  $db_connection = new mysqli($dbhost, $dbuser, $dbpassword, $database);
    if ($db_connection->connect_error) {
        die($db_connection->connect_error);
  }

  $sqlQuery = "select Transcript from $applicationsTable where Directory_ID= '$id'";
  $result = mysqli_query($db_connection, $sqlQuery);
  if (!$result) {
        die("Retrieval of courses failed: ". $db_connection->error);
  } else {
      $result->data_seek(0);
      $recordArray = $result->fetch_array(MYSQLI_ASSOC);

      $bytes = $recordArray['Transcript'];
      header("Content-type: application/pdf");
      
      print $bytes;

  		//header("Content-type: application/pdf");
  		//echo $recordArray['Transcript'];
  		//mysqli_free_result($result);

  }
?>
