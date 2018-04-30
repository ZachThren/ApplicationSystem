<?php
    require_once "support.php";
    require_once "dblogin.php";

    //retrieving fields from form session
    session_start();

    $course = $_SESSION["course"];
    $sortby = $_SESSION["sortby"];
    $undergraduate = $_SESSION["undergraduate"];
    $graduate = $_SESSION["graduate"];
    $applicationsTable = $_SESSION["applicationsTable"];
    $coursesTable = $_SESSION["coursesTable"];

    //Building the heads of the table
    $applying_table_head =  ['First', 'Last', 'Email', 'Directory_ID', 'GPA', 'Degree', 'Experience',"Transcript","Extra Information"];
    $accepted_table_head = ['First', 'Last', 'Email', 'Directory_ID', 'GPA', 'Degree', 'Experience',"Transcript","Extra Information"];

    $applying_table = <<<THEAD

    <div>
        <h1>Applications for {$course}</h1>
    </div>
    <table class="table table-striped">
        <tr>
THEAD;

    $accepted_table = <<<TABLE2
    <div>
        <h1>Current TAs for {$course}</h1>
    </div>
    <table class="table table-striped">
        <tr>
TABLE2;

    foreach($applying_table_head as $key=>$value) {
        $applying_table .= "<th>$value</th>";
    }

    foreach($accepted_table_head as $key=>$value) {
        $accepted_table .= "<th>$value</th>";
    }

    $applying_table .= "</tr>";
    $accepted_table .= "</tr>";


    // connecting to database;
    $db_connection = new mysqli($dbhost, $dbuser, $dbpassword, $database);
    if ($db_connection->connect_error) {
        die($db_connection->connect_error);
    }

    //retrieving data from courses table
    $course_query = "select Course, Applying_Undergraduate, Applying_Graduate, Accepted_Undergraduate, Accepted_Graduate from {$coursesTable} where Course = '{$course}'";
    $applying_TAs = [];
    $accepted_TAs = [];

    $result1 = $db_connection->query($course_query);
    if (!$result1) {
        die("Retrieval of courses failed: ". $db_connection->error);
    } else {
        $num_rows = $result1->num_rows;
        if ($num_rows === 0) {
            echo "Empty Table<br>";
        } else {
            $result1->data_seek(0);
            $row = $result1->fetch_array(MYSQLI_ASSOC);

            $applying_Undergraduate = unserialize($row["Applying_Undergraduate"]);
            $applying_Graduate = unserialize($row["Applying_Graduate"]);
            $accepted_Undergraduate = unserialize($row["Accepted_Undergraduate"]);
            $accepted_Graduate = unserialize($row["Accepted_Graduate"]);

            if (empty($applying_Undergraduate)) {
                $applying_Undergraduate = [];
            }
            if (empty($applying_Graduate)) {
                $applying_Graduate = [];
            }

            if (empty($accepted_Undergraduate)) {
                $accepted_Undergraduate = [];
            }
            if (empty($accepted_Graduate)) {
                $accepted_Graduate = [];
            }

            if ($undergraduate != "Undergraduate" && $graduate == "Graduate") {
                $applying_Undergraduate = [];
                $accepted_Undergraduate = [];
            }
            if ($undergraduate == "Undergraduate" && $graduate != "Graduate") {
                $applying_Graduate = [];
                $accepted_Graduate = [];
            }

            $applying_TAs = array_merge($applying_Undergraduate, $applying_Graduate);
            $accepted_TAs = array_merge($accepted_Undergraduate, $accepted_Graduate);
        }
    }


    //retrieving data from Applications table add constructing bodies of tables
    $fields = ['First', 'Last', 'Email', 'Directory_ID', 'GPA', 'Degree', "Previous","Transcript"];
    $fieldsQuery = implode(", ", $fields);
    $applications_query = "select {$fieldsQuery} from {$applicationsTable} order by {$sortby} DESC";

    $result2 = $db_connection->query($applications_query);
    if (!$result2) {
        die("Retrieval failed: ". $db_connection->error);
    } else {
        $num_rows = $result2->num_rows;

        if ($num_rows === 0) {
            echo "No Applications<br>";
        } else {
            for ($row_index = 0; $row_index < $num_rows; $row_index++) {
                $result2->data_seek($row_index);
                $row = $result2->fetch_array(MYSQLI_ASSOC);

                if (empty($applying_TAs) == false) {
                    if (in_array($row['Directory_ID'], $applying_TAs)) {
                        $applying_table .= "<tr>";
                        foreach($row as $columKey=>$columValue) {
                            if ($columKey == "Transcript") {
                                $applying_table .= "<form action='transcript.php' method='post'>"
                                $applying_table .= "<td><input type='hidden' name='transcript' value='{$row['Directory_ID']}'>";
                                $applying_table .= "<button class='btn btn-primary' type='submit' >Transcript</button></td>"
                                $applying_table .= "</form>"
                            } else if ($columKey == "Previous") {
                                $previous_course = unserialize($columValue);
                                if (empty($previous_course)) {
                                    $applying_table .= "<td>No Previous Experience</td>";
                                } else {
                                    $previous_course_str = implode(', ', $previous_course);
                                    $applying_table .= "<td>{$previous_course_str}</td>";
                                }

                            } else {
                                $applying_table .= "<td>{$columValue}</td>";
                            }
                        }

                        $applying_table .= "<td><input type='submit' class='btn btn-warning' value='More Info {$row['Directory_ID']}' name ='moreInfo{$row['Directory_ID']}' data-toggle='modal' data-target='#myModal' id='{$row['Directory_ID']}' onclick='showDetails(this);' ></td>";

                        $applying_table .= "</tr>";
                    }
                }

                if (empty($accepted_TAs) == false) {
                    if (in_array($row['Directory_ID'], $accepted_TAs)) {
                        $accepted_table .= "<tr>";
                        foreach($row as $columKey=>$columValue) {
                            if ($columKey == "Transcript") {
                                $accepted_table .= "<td><dvi class='btn btn-primary' onClick='showTranscript({$row['Directory_ID']})' value='{$row['Directory_ID']}'>Transcript</dvi></td>";
                            } else if ($columKey == "Previous") {
                                $previous_course = unserialize($columValue);
                                if (empty($previous_course)) {
                                    $accepted_table .= "<td>No Previous Experience</td>";
                                } else {
                                    $previous_course_str = implode(', ', $previous_course);
                                    $accepted_table .= "<td>{$previous_course_str}</td>";
                                }

                            } else {
                                $accepted_table .= "<td>{$columValue}</td>";
                            }
                        }

                        $accepted_table .= "<td><input type='submit' class='btn btn-warning' value='More Info {$row['Directory_ID']}' name ='moreInfo{$row['Directory_ID']}' data-toggle='modal' data-target='#myModal' id='{$row['Directory_ID']}' onclick='showDetails(this);' ></td>";

                        $accepted_table .= "</tr>";
                    }
                }
            }
        }
    }

    $applying_table .= "</table>";
    $accepted_table .= "</table>";

    $modal = <<<EOMODAL
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

      <div class="modal-dialog">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel">Student Information</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <p>First Name: <span id="first"></span></p>
            <p>Last Name: <span id="last"></span></p>
            <p>Email: <span id="email"></span></p>
            <p>Directory ID: <span id="directoryid"></span></p>
            <p>GPA: <span id="gpa"></span></p>

            <p>Degree: <span id="degree"></span></p>
            <p>Position Type: <span id="type"></span></p>
            <p>Want To Teach: <span id="pref"></span></p>
            <p>Currently a TA: <span id="currentta"></span></p>
            <p>TAing for Current Course: <span id="currentcourse"></span></p>
            <p>Instructor for that Course: <span id="instructor"></span></p>
            <p>Advisor: <span id="advisor"></span></p>

            <p>Taking UMEI: <span id="takenumei"></span></p>
            <p>Passed UMEI: <span id="passedumei"></span></p>
            <p>Extra Info: <span id="note"></span></p>
          </div>

          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>

        </div>
      </div>
    </div>

    <script>
    function showDetails(button) {
      var id = button.id;
      //ajax call to get student Information
      $.ajax({
        url: "student.php",
        method: "GET",
        data: {"id": id},
        success: function(response) {
          var student = JSON.parse(response);
          var pref = "No";


          $("#first").text(student.First);
          $("#last").text(student.Last);
          $("#email").text(student.Email);
          $("#directoryid").text(student.Directory_ID);
          $("#gpa").text(student.GPA);

          $("#degree").text(student.Degree);
          $("#type").text(student.Position_Type);
          if (student.Want_Teach == 1) {
            pref = "Yes"
          } else {
            pref = "Yes"
          }
          $("#pref").text(pref);
          if (student.Current_TA == 1) {
            pref = "Yes"
          } else {
            pref = "Yes"
          }
          $("#currentta").text(pref);
            $("#currentcourse").text(student.Current_Course);
              $("#instructor").text(student.Current_Instructor);
          $("#advisor").text(student.Advisor);
          if (student.Taking_UMEI == 1) {
            pref = "Yes"
          } else {
            pref = "Yes"
          }
          $("#takenumei").text(pref);
          if (student.Passed_MEI == 1) {
            pref = "Yes"
          } else {
            pref = "Yes"
          }
            $("#passedumei").text(pref);

          $("#note").text(student.Extra_Information);
        }

      });

    }

    </script>

EOMODAL;

    $applying_table .= $modal;

    $accepted_table .= $modal;


    if (empty($accepted_TAs)) {
        $accepted_table .= "<p> There are no TAs currently assigned to this class </p>";
    }

    if (empty($applying_TAs)) {
        $applying_table .= "<p> There are no TAs currently applying to this class </p>";
    }

    $applying_table .= "<hr style='height:1px;border:none;color:#C0C0C0;background-color:#C0C0C0;' /></form>";
    $accepted_table .= "<hr style='height:1px;border:none;color:#C0C0C0;background-color:#C0C0C0;' /></form>";


    $homeForm = <<<EOFORM
        <form action = "faculty.php" method='post' align="left" style="margin-left: 20px">
        <input type="submit" class="btn btn-info" name="goback" value="Choose Another Course">
        </form>
EOFORM;



    $body = $accepted_table.$applying_table."<br>".$homeForm;
    echo generatePage($body, "Display Administrative");
?>
