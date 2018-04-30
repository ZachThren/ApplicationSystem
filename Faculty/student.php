<?php
require_once "support.php";
require_once "dblogin.php";

// connecting to database;
$db_connection = new mysqli($dbhost, $dbuser, $dbpassword, $database);
if ($db_connection->connect_error) {
    die($db_connection->connect_error);
}
$id = $_GET["id"];
$studentInfo_query = "select First, Last, Email, Directory_ID, GPA, Courses,  Previous, Degree, Position_Type, Want_Teach, Advisor, Passed_MEI, Extra_Information from Applications_Spring_2018 where Directory_ID = '{$id}'";
$result = $db_connection->query($studentInfo_query);

if (!$result) {
    die("Retrieval of student information failed: ". $db_connection->error);
} else {
  $result->data_seek(0);
  $student = $result->fetch_array(MYSQLI_ASSOC);

  // json_encode(unserialize({$student['Courses']}));
  echo json_encode($student);
}



 ?>
