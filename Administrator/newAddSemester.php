<?php
  require_once("support.php");
  require_once("dblogin.php");

  $db = new mysqli($dbhost, $dbuser, $dbpassword, $database);
  if ($db->connect_error) {
    die($db->connect_error);
  } else {
    $query = "select season, year from Semesters order by ID desc limit 1";
    $result->data_seek(0);
    $actual_result = $result->fetch_array(MYSQLI_ASSOC);

    if ($actual_result['season'] == "Winter") {
      $this_season = "Spring";
      $this_year = $actual_result['year'];
    } else if ($actual_result['season'] == "Spring") {
      $this_season = "Summer";
      $this_year = $actual_result['year'];
    } else if ($actual_result['season'] == "Summer") {
      $this_season = "Fall";
      $this_year = $actual_result['year'];
    } else {
      $this_season = "Winter";
      $this_year = (string)($actual_result['year'] + 1);
    }
  }
?>
