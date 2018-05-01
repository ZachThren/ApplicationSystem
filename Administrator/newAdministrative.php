<?php
  require_once("support.php");
  require_once("dblogin.php");

  $db = new mysqli($dbhost, $dbuser, $dbpassword, $database);
  if ($db->connect_error) {
    die($db->connect_error);
  } else {
    if (isset($_POST["submit"])) {
      $query = "create table Applications_{$_POST["season"]}_{$_POST["year"]} (
        First varchar(20),
        Last varchar(20),
        Email varchar(32),
        Directory_ID varchar(32) primary key,
        GPA float,
        Courses varchar(200),
        Previous varchar(300),
        Degree enum('Undergraduate','MS','PhD'),
        Transcript longblob,
        Position_Type enum('Full','Part'),
        Want_Teach tinyint,
        Advisor varchar(32),
        Current_TA tinyint,
        Current_Step enum('1','2','3'),
        Current_Course varchar(32),
        Current_Instructor varchar(32),
        Passed_MEI tinyint,
        Taking_UMEI tinyint,
        Extra_Information varchar(500)
      )";
      $result = $db->query($query);
      $query = "create table Courses_{$_POST["season"]}_{$_POST["year"]} (
        Course varchar(50) primary key,
        Applying_Undergraduate varchar(1500),
        Applying_Graduate varchar(1000),
        Accepted_Undergraduate varchar(1500),
        Accepted_Graduate varchar(1000),
        Max_Undergraduate int,
        Max_Graduate int,
        Max_Total int
      )";
      $result = $db->query($query);
      $courses = array();
      $total_courses = 0;
      foreach ($_POST as $key => $value) {
        if ((strlen($key) > 6) && (substr($key, 0, 6) == "course")) {
          $courses[] = substr($key, 6, strlen($key) - 6);
          $total_courses++;
        }
      }
      foreach ($courses as $key) {
        $maxUndergrad = (($_POST["maxTA$key"] / 3) * 2);
        $maxGrad = $_POST["maxTA$key"] / 3;
        $query = "insert into Courses_{$_POST["season"]}_{$_POST["year"]}
          (Course, Max_Undergraduate, Max_Graduate, Max_Total) values (
            \"{$_POST["course$key"]}\",
            {$maxUndergrad},
            {$maxGrad},
            {$_POST["maxTA$key"]}
          )";
        $result = $db->query($query);
      }
      $query = "insert into Semesters (Season, Year, NumOfCourses) values
        (\"{$_POST["season"]}\", \"{$_POST["year"]}\", $total_courses)";
      $result = $db->query($query);
    }
    $body = "<br><div id=\"accordion\">";
    $query = "select season, year from Semesters order by ID desc";
    $result = $db->query($query);
    if (!$result) {
      die("Retrieval failed: ". $db->error);
    } else {
      if ($result->num_rows > 0) {
        for ($index = 0; $index < $result->num_rows; $index++) {
          $result->data_seek($index);
          $row = $result->fetch_array(MYSQLI_ASSOC);
          if ($index == 0) {
            $option1 = "collapsed";
            $option2 = "true";
            $option3 = "show";
          } else {
            $option1 = "";
            $option2 = "false";
            $option3 = "";
          }
          if (($row["season"] == "Winter" && $row["year"] == 2017) || ($row["season"] == "Spring" && $row["year"] == 2017)
              || ($row["season"] == "Summer" && $row["year"] == 2017) || ($row["season"] == "Fall" && $row["year"] == 2017)
              || ($row["season"] == "Winter" && $row["year"] == 2018)) {
            $option4 = "disabled";
          } else {
            $option4 = "";
          }
          $body .= <<<EOBODY
            <div class="card">
              <div class="card-header" id="heading$index">
                <h5 class="mb-0">
                  <button class="btn btn-link $option1" data-toggle="collapse" data-target="#collapse$index" aria-expanded="$option2" aria-controls="collapse$index">
                    {$row["season"]} {$row["year"]}
                  </button>
                </h5>
              </div>
              <div id="collapse$index" class="collapse $option3" aria-labelledby="heading$index" data-parent="#accordion">
                <div class="card-body">
                  <form action="autofill.php" method="post">
                    <input type="hidden" name="coursesTable" value="Courses_{$row["season"]}_{$row["year"]}" />
                    <input type="hidden" name="applicationsTable" value="Applications_{$row["season"]}_{$row["year"]}" />
                    <button type="submit" class="btn btn-info continueButton" $option4>Automatically Assign TAs</button>
                  </form>
                  <br>
                  <form action="manualfill.php" method="post">
                    <input type="hidden" name="coursesTable" value="Courses_{$row["season"]}_{$row["year"]}" />
                    <input type="hidden" name="applicationsTable" value="Applications_{$row["season"]}_{$row["year"]}" />
                    <button type="submit" class="btn btn-info continueButton" $option4>Manually Assign TAs</button>
                  </form>
                </div>
              </div>
            </div>
EOBODY;
        }
      } else {
        $body .= "<p>There are no semesters in the database</p>";
      }
    }

    $body .= "<br><a class=\"btn btn-primary addButton\" href=\"newAddSemester.php\" role=\"button\">Add Semester</a></div>";
    $body .= "<hr style='height:1px; border:none; color: white; background-color: white;'/>";

    $header = <<<headx
    <div style="margin: auto; width: 50%">
    <h1> Choose Term</h1>
headx;

    $finalBody = $header.$body."</div>";
    echo generatePage($finalBody, "Administrative");
  }
?>
