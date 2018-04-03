<?php
require_once("supportAS.php");
require_once("Login.php");

session_start();
$course = $_SESSION['course'];

$db_connection = new mysqli($dbhost, $dbuser, $dbpassword, $database);
if ($db_connection->connect_error) {
    die($db_connection->connect_error);
}
$query = "select * from Courses where Course = $course";
if (!$result) {
  die("Retrieval failed: ". $db_connection->error);
} else {
  /* Number of rows found */
  $acceptedTAs = [];
  $applyingTAs = [];
  $num_rows = $result->num_rows;
  if ($num_rows === 0) {
    echo "Currently There Are TA for This Course<br>";
  } else {
      $row = $result->fetch_array(MYSQLI_ASSOC);
      $body .= "<option name='course' value='{$row['Course']}>{$row['Course']}</option>";
      $acceptedTAs = unserialize($row['AcceptedTAs']);
      $applyingTAs = unserialize($row['ApplyingTAs']);
      $body .= "<h2><strong>Assinged TAs for $course</strong></h2>";
      $body .= "<table>";
      $body .= "<tr>";
      $body .= "<th>Name</th>";
      $body .= "</tr>";
      foreach($acceptedTAs as $ta) {
        $body .= "<tr>";
        $body .= "<td>$ta</td>";
        $body .= "</tr>";
      }
      $body .= "<br><br><br>";

      $body .= "<h2><strong>Unassigned Applicants for $course</strong></h2>";
      $body .= "<table>";
      $body .= "<tr>";
      $body .= "<th>Name</th>";
      $body .= "</tr>";
      foreach($applyingTAs as $ta) {
        $body .= "<tr>";
        $body .= "<td>$ta</td>";
        $body .= "</tr>";
      }
  }
}

$page = generatePage($body);
echo $page;
 ?>
