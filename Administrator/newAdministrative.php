<?php
  require_once("support.php");
  require_once("dblogin.php");

  $db = new mysqli($dbhost, $dbuser, $dbpassword, $database);
  if ($db->connect_error) {
    die($db->connect_error);
  } else {
    if (isset($_POST["submit"])) {
      $query = "create table testApplications_{$_POST["season"]}_{$_POST["year"]} (
        First varchar(20),
        Last varchar(20),
        Email varchar(32),
        Directory_ID varchar(32) primary key,
        GPA float,
        Courses varchar(200),
        Previous varchar(300),
        Degree enum('Undergraduate','MS','PhD'),
        Transcript blob,
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
      );
      $result = $db->query($query);
      $query = "create table testCourses_{$_POST["season"]}_{$_POST["year"]} (
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
      $total_courses = ((count($_POST) - 3) / 2);
      for ($index = 1; $index <= $total_courses; $index++) {
        $query = "insert into Courses_{$_POST["season"]}_{$_POST["year"]}
          (Course, Max_Total) values (\"{$_POST["course$index"]}\", {$_POST["maxTA$index"]})";
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
          $body .= <<<EOBODY
            <div class="card">
              <div class="card-header" id="heading$index">
                <h5 class="mb-0">
                  <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse$index" aria-expanded="false" aria-controls="collapse$index">
                    {$row['season']} {$row['year']}
                  </button>
                </h5>
              </div>
              <div id="collapse$index" class="collapse" aria-labelledby="heading$index" data-parent="#accordion">
                <div class="card-body">
<<<<<<< HEAD
                  <form action="{$_SERVER["PHP_SELF"]}" method="post"> 
                    <button type="submit" name="auto" class="btn btn-primary">Automatically Assign TAs</button> 
                    <button type="submit" name="manual" class="btn btn-primary">Manually Assign TAs</button>
                  </form>
=======
                  <a href="autofill.php" class="btn btn-primary">Automatically Assign TAs</a>
                  <br><br>
                  <a href="manualfill.php" class="btn btn-primary">Manually Assign TAs</a>
>>>>>>> 5743860fc93a1f87b4616e6ad6bc91b8db54b06b
                </div>
              </div>
            </div>
EOBODY;
        }
      } else {
        $body .= "<p>There are no semesters in the database</p>";
      }
    }

<<<<<<< HEAD
    if (isset($_POST["auto"])) {
      header("Location: autofill.php");
    }

    if (isset($_POST["manual"])) {
        header("Location: autofill.php");
    }
=======
>>>>>>> 5743860fc93a1f87b4616e6ad6bc91b8db54b06b
    $body .= "<br><a class=\"btn btn-primary\" href=\"newAddSemester.php\" role=\"button\">Add Semester</a></div>";
    $body .= "<hr style='height:1px; border:none; color: white; background-color: white;'/>";


    $finalBody = $body;
    echo generatePage($finalBody, "Administrative");
  }
?>
