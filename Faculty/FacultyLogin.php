<?php
require_once("supportAS.php");
require_once("Login.php");

$user = "main";
$password = "terps";

if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) &&
    $_SERVER['PHP_AUTH_USER'] == $user && $_SERVER['PHP_AUTH_PW'] == $password){
      if(isset($_POST['submitCourse'])){
        session_start();
        $_SESSION['course'] = $_POST['course'];

        header("location: ProcessCourse.php");
      } else {
      $body = "<form action='FacultyLogin.php' method='post'>";
      $body .= "<h1><strong>Choose a Course</strong></h1>";
      $db_connection = new mysqli($dbhost, $dbuser, $dbpassword, $database);
      if ($db_connection->connect_error) {
          die($db_connection->connect_error);
      }
      $query = "select course from Courses";
      if (!$result) {
        die("Retrieval failed: ". $db_connection->error);
      } else {
        /* Number of rows found */
        $num_rows = $result->num_rows;
        if ($num_rows === 0) {
          echo "No Courses Are Currently For TA<br>";
        } else {
          for ($row_index = 0; $row_index < $num_rows; $row_index++) {
            $result->data_seek($row_index);
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $body .= "<option name='course' value='{$row['Course']}>{$row['Course']}</option>";
          }
        }
      }
      $body .= "<input type='submit' name='submitCourse'>";
      $body .= "</form>"
    }
	$page = generatePage($body);
	echo $page;
} else {
  header("WWW-Authenticate: Basic realm=\"Example System\"");
	header("HTTP/1.0 401 Unauthorized");
}

?>
