<?php
  require_once("support.php");
  require_once("dblogin.php");

  $db = new mysqli($dbhost, $dbuser, $dbpassword, $database);
  if ($db->connect_error) {
    die($db->connect_error);
  } else {
    $body = "<br><div id=\"accordion\">";
    $query = "select name from Semesters";
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
                  <button class="btn btn-link" data-toggle="collapse" data-target="#collapse$index" aria-expanded="true" aria-controls="collapse$index">
EOBODY;
          foreach ($row as $value) {
            $body .= "$value";
          }
          $body .= <<<EOBODY
                  </button>
                </h5>
              </div>
              <div id="collapse$index" class="collapse show" aria-labelledby="heading$index" data-parent="#accordion">
                <div class="card-body">
                  <button type="button" class="btn btn-primary">Automatically Assign TAs</button>
                  <br><br>
                  <button type="button" class="btn btn-primary">Manually Assign TAs</button>
                </div>
              </div>
            </div>
EOBODY;
        }
      } else {
        $body .= "<p>There are no semesters in the database</p>";
      }
    }
    $body .= "<br><a class=\"btn btn-primary\" href=\"newAddSemester.php\" role=\"button\">Add Semester</a></div>";
    echo generatePage($body, "Administrative");
  }
?>
