<?php
  require_once("support.php");
  require_once("dblogin.php");

  $db = new mysqli($dbhost, $dbuser, $dbpassword, $database);
  if ($db->connect_error) {
    die($db->connect_error);
  } else {
    $query = "select * from Semesters order by ID desc limit 1";
    $result = $db->query($query);
    $result->data_seek(0);
    $result_array = $result->fetch_array(MYSQLI_ASSOC);
    if ($result_array['Season'] == "Winter") {
      $this_season = "Spring";
      $this_year = $result_array['Year'];
      $past_year = (string)($this_year - 1);
      $query = "select numOfCourses from Semesters where Season=\"Fall\" and Year=\"$past_year\"";
      $result = $db->query($query);
      $result->data_seek(0);
      $result_array = $result->fetch_array(MYSQLI_ASSOC);
      $courses = $result_array['numOfCourses'];
      $query = "select Course, Max_Total from Courses_Fall_{$past_year}";
    } else if ($result_array['Season'] == "Spring") {
      $this_season = "Summer";
      $this_year = $result_array['Year'];
      $past_year = (string)($this_year - 1);
      $query = "select numOfCourses from Semesters where Season=\"Summer\" and Year=\"$past_year\"";
      $result = $db->query($query);
      $result->data_seek(0);
      $result_array = $result->fetch_array(MYSQLI_ASSOC);
      $courses = $result_array['numOfCourses'];
      $query = "select Course, MaxTotal from Courses_Summer_{$past_year}";
    } else if ($result_array['Season'] == "Summer") {
      $this_season = "Fall";
      $this_year = $result_array['Year'];
      $query = "select numOfCourses from Semesters where Season=\"Spring\" and Year=\"$this_year\"";
      $result = $db->query($query);
      $result->data_seek(0);
      $result_array = $result->fetch_array(MYSQLI_ASSOC);
      $courses = $result_array['numOfCourses'];
      $query = "select Course, MaxTotal from Courses_Spring_{$this_year}";
    } else {
      $this_season = "Winter";
      $this_year = (string)($result_array['Year'] + 1);
      $past_year = (string)($this_year - 1);
      $query = "select numOfCourses from Semesters where Season=\"Winter\" and Year=\"$past_year\"";
      $result = $db->query($query);
      $result->data_seek(0);
      $result_array = $result->fetch_array(MYSQLI_ASSOC);
      $courses = $result_array['numOfCourses'];
      $query = "select Course, MaxTotal from Courses_Winter_{$past_year}";
    }
    $result = $db->query($query);
    $body = <<<EOBODY
      <form action="newAdministrative.php" method="post">
        <div class="form-row">
          <div class="col">
            <strong>Season</strong></br>
            <input type="text" class="form-control" name="season" value="$this_season"/>
          </div>
          <div class="col">
            <strong>Year</strong></br>
            <input type="text" class="form-control" name="year" value="$this_year"/>
          </div>
        </div>
EOBODY;
    for ($index = 1; $index <= $courses; $index++) {
      $result->data_seek($index - 1);
      $result_array = $result->fetch_array(MYSQLI_ASSOC);
      $body .= <<<EOBODY
        <div class="form-row">
          <div class="col">
            <strong>Course #$index</strong></br>
            <input type="text" class="form-control" name="course$index" value="{$result_array['Course']}"/>
          </div>
          <div class="col">
            <strong>Maximum TAs</strong></br>
            <input type="number" class="form-control" name="maxTA$index" value="{$result_array['Max_Total']}"/>
          </div>
        </div>
EOBODY;
    }
    $body .= <<<EOBODY
        <br><input type="submit" class="form-control" value="Create Semester" name="submit"/><br>
        <a class="btn btn-primary" href="newAdministrative.php" role="button">Go Back</a>
      </form>
EOBODY;
    echo generatePage($body, "Add Semester");
  }
?>
