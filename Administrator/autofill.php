<?php
    require_once "support.php";
    require_once "dblogin.php";
    require_once "courses.php";

    session_start();
    
    $applicationsTable = $_POST["applicationsTable"];
    $coursesTable = $_POST["coursesTable"];

    if (isset($_POST["coursesTable"])) {
        $coursesTable = $_POST["coursesTable"];
    }
    if (isset($_POST["applicationsTable"])) {
        $applicationsTable = $_POST["applicationsTable"];
    }

    $_SESSION["coursesTable"] = $coursesTable;
    $_SESSION["applicationsTable"] = $applicationsTable;
    
    // connecting to database;
    $db_connection = new mysqli($dbhost, $dbuser, $dbpassword, $database);
    if ($db_connection->connect_error) {
        die($db_connection->connect_error);
    }
    $course_query = "select Course, Applying_Undergraduate, Applying_Graduate, Accepted_Undergraduate, Accepted_Graduate, Max_Undergraduate, Max_Graduate, Max_Total from {$coursesTable} order by 'Course'";
    $result0 = mysqli_query($db_connection, $course_query);
    if (!$result0) {
        die("Retrieval of courses failed: ". $db_connection->error);
    } else {
        $num_rows_course = $result0->num_rows;
        if ($num_rows_course === 0) {
            echo "Empty Table<br>";
        } else {
            //iterating through the courses
            for ($course_index = 0; $course_index < $num_rows_course; $course_index++) {
                $result0->data_seek($course_index);
                $a_course = $result0->fetch_array(MYSQLI_ASSOC);
                $currName = $a_course['Course'];
                //initializing the data for the current course
                $new_Max_Ugrad = $a_course["Max_Undergraduate"];
                $new_Max_Grad = $a_course["Max_Graduate"];
                $applying_Undergraduate = unserialize($a_course["Applying_Undergraduate"]);
                $applying_Graduate = unserialize($a_course["Applying_Graduate"]);
                $accepted_Undergraduate = unserialize($a_course["Accepted_Undergraduate"]);
                //$accepted_Graduate = unserialize($a_course["Accepted_Graduate"]);
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

                $applications_query = "select Directory_ID, GPA, Degree, Courses from {$applicationsTable} order by GPA DESC";
                $result2 = mysqli_query($db_connection, $applications_query);
                if (!$result2) {
                    die("Retrieval failed: ". $db_connection->error);
                } else {
                    $num_rows = $result2->num_rows;
                    if ($num_rows === 0) {
                        echo "No Applications<br>";
                    } else {
                        $addedUndergrad = 0;
                        $addedGrad = 0;
                        for ($row_index = 0; $row_index < $num_rows; $row_index++) {
                            $result2->data_seek($row_index);
                            $row = $result2->fetch_array(MYSQLI_ASSOC);
                            $applyingTo = unserialize($row['Courses']);

                            if (empty($applyingTo)) {
                                $applyingTo = [];
                            }

                            if ($addedUndergrad >= $new_Max_Ugrad && $addedGrad >= $new_Max_Grad) {
                                break;
                            }
                            if ($row["Degree"] == "Undergraduate" && $addedUndergrad < $new_Max_Ugrad && in_array($currName, $applyingTo)) {
                                array_push($accepted_Undergraduate, $row["Directory_ID"]);
                                $addedUndergrad = $addedUndergrad + 1;
                            } else if ($addedGrad < $new_Max_Grad && in_array($currName, $applyingTo)) {
                                array_push($accepted_Graduate, $row["Directory_ID"]);
                                $addedGrad = $addedGrad + 1;
                            }
                        }
                    }
                    $updated_applying_u = [];
                    foreach($applying_Undergraduate as $key => $value) {
                        if (!(in_array($value, $accepted_Undergraduate))) {
                            array_push($updated_applying_u, $value);
                        }
                    }
                    $updated_applying_g = [];
                    foreach($applying_Graduate as $key => $value) {
                        if (!(in_array($value, $accepted_Graduate))) {
                            array_push($updated_applying_g, $value);
                        }
                    }
                    $final_applying_undergraduate = serialize($updated_applying_u);
                    $final_applying_graduate = serialize($updated_applying_g);
                    $final_accepted_undergraduate = serialize($accepted_Undergraduate);
                    $final_accepted_graduate = serialize($accepted_Graduate);
                    $update_query = "update {$coursesTable} ";
                    $update_query .= "set Applying_Undergraduate = '".$final_applying_undergraduate."', Applying_Graduate = '".$final_applying_graduate."', Accepted_Undergraduate = '".$final_accepted_undergraduate."', Accepted_Graduate = '".$final_accepted_graduate."' ";
                    $update_query .= "where Course = '{$currName}'";
                    $result3 = $db_connection->query($update_query);
                    if (!$result3) {
                        die("Retrieval of courses failed: ". $db_connection->error);
                    } else {
                        

                    }
                }
            }
        }

        $display_query = "select Course, Max_Undergraduate, Max_Graduate, Max_Total  from {$coursesTable} order by Course";
        $result4 = mysqli_query($db_connection, $display_query);

        if ($result4) {
            $numberOfRows = mysqli_num_rows($result4);
            if ($numberOfRows == 0) {
                $body = "<h2>No entries exists in the table</h2>";
            } else {
                $body = <<<EOBODY
                    <table class="table table-stripped table-bordered">
                    <thead>                    
                        <th><div style="text-align:center">Course</div></th>
                        <th><div style="text-align:center">Undergraduate TAs</div></th>
                        <th><div style="text-align:center">Graduate TAs</div></th>
                        <th><div style="text-align:center">Total TAs</div></th>
                    </thead>
                    <tbody>
EOBODY;
                while ($recordArray = mysqli_fetch_array($result4, MYSQLI_ASSOC)) {
                    $crse = $recordArray['Course'];
                    $Max_u = $recordArray['Max_Undergraduate'];
                    $Max_grad = $recordArray['Max_Graduate'];
                    $Max_Tot = $recordArray['Max_Total'];

                    $body .= "<tr>";
                    $body .= "<td><div style=\"text-align:center\">".$crse."</div> </td>";
                    $body .= "<td><div style=\"text-align:center\">".$Max_u."</div> </td>";
                    $body .= "<td><div style=\"text-align:center\">".$Max_grad."</div> </td>";
                    $body .= "<td><div style=\"text-align:center\">".$Max_Tot."</div> </td>";
                    $body .= "</tr>";
                }

            }
        } else {
            $body = "Retrieving records failed." . mysqli_error($db_connection);
        }
    }

    $body .= "<div class='mycontainer'><h4>TAs were successfully assigned to classes.</h4> <h4>Return Home. You will be able to add/remove TAs manually</h4></div>";

    $page = generatePage($body, "autofill");
    echo $page;

    function connectToDB($host, $user, $password, $database) {
        $db = mysqli_connect($host, $user, $password, $database);
        if (mysqli_connect_errno()) {
            echo "Connect failed.\n".mysqli_connect_error();
            exit();
        }
        return $db;
    }
    
?>