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
          <div class="form-group col-sm-2 offset-sm-2 text-center mt-2">
            <strong>Season</strong>
          </div>
          <div class="form-group col-sm-2">
            <input type="text" class="form-control" name="season" value="$this_season"/>
          </div>
          <div class="form-group col-sm-2 text-center mt-2">
            <strong>Year</strong>
          </div>
          <div class="form-group col-sm-2">
            <input type="text" class="form-control" name="year" value="$this_year"/>
          </div>
        </div>
        <br>
        <script>
          let rows = new Set();
        </script>
EOBODY;
    for ($index = 1; $index <= $courses; $index++) {
      $result->data_seek($index - 1);
      $result_array = $result->fetch_array(MYSQLI_ASSOC);
      $body .= <<<EOBODY
        <div class="form-row" id="row$index">
          <div class="form-group col-sm-2 offset-sm-2 text-center mt-2">
            Course Name
          </div>
          <div class="form-group col-sm-2">
            <input type="text" class="form-control" name="course$index" value="{$result_array['Course']}"/>
          </div>
          <div class="form-group col-sm-2 text-center mt-2">
            Maximum TAs
          </div>
          <div class="form-group col-sm-2">
            <input type="number" min="0" class="form-control" name="maxTA$index" value="{$result_array['Max_Total']}"/>
          </div>
          <div class="col-xs-1">
            <button type="button" class="btn btn-default delete $index">Delete</button>
          </div>
        </div>
        <script>
          rows.add($index);
        </script>
EOBODY;
    }
    $body .= <<<EOBODY
        <div class="form-group col-sm-4 offset-sm-4">
          <button type="button" class="btn btn-default btn-block add">Add Course</button>
        </div>
        <div class="form-group col-sm-8 offset-sm-2">
          <button type="submit" class="btn btn-default btn-block" name="submit"/>Create Semester</button>
          <button class="btn btn-primary btn-block" href="newAdministrative.php">Go Back</button>
        </div>
      </form>
      <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
      <script>
        $(document).ready(function(){
          $(document).on('click', ".add", function(){
            let addTo = "#row" + Math.max.apply(Math, [...rows]);
            next = Math.max.apply(Math, [...rows]) + 1;
            rows.add(next);
            let newIn = '<div class="form-row" id="row'+ next +'"><div class="form-group col-sm-2 offset-sm-2 text-center mt-2">Course Name</div><div class="form-group col-sm-2"><input type="text" class="form-control" name="course'+ next +'"/></div><div class="form-group col-sm-2 text-center mt-2">Maximum TAs</div><div class="form-group col-sm-2"><input type="number" min="0" class="form-control" name="maxTA'+ next +'"/></div><div class="col-xs-1"><button type="button" class="btn btn-default delete '+ next +'">Delete</button></div></div>';
            let newInput = $(newIn);
            $(addTo).after(newInput);
          });
          $(document).on('click', ".delete", function(){
            rows.delete(parseInt(this.className.split(" ")[3]));
            $(this).closest(".form-row").remove();
          });
        });
      </script>
EOBODY;
    echo generatePage($body, "Add Semester");
  }
?>
