<?php
    require_once "support.php";
    require_once "dblogin.php"; 

    //retrieving fields from form
    session_start();
    $course = $_SESSION["course"];
    $sortby = $_SESSION["sortby"];

    $fields = ['Name', 'Email', 'DirectoryID', 'gpa'];
    $tableHead = ['Name', 'Email', 'ID', 'GPA', 'Resume', 'ADD TA'];
    $tableHead2 = ['Name', 'Email', 'ID', 'GPA', 'Resume', 'REMOVE TA'];

    $table = <<<THEAD
    <div>
        <h1>Applications for {$course}</h1>
    </div>
    <table class="table table-striped">
        <tr>

THEAD;

    $table2 = <<<TABLE2
    <div>
        <h1>Current TAs for {$course}</h1>
    </div>
    <table class="table table-striped">
        <tr>

TABLE2;
    // connecting to database;
    $db_connection = new mysqli($dbhost, $dbuser, $dbpassword, $database);
    if ($db_connection->connect_error) {
        die($db_connection->connect_error);
    }

    //Analysing the fields field from the form to determine what colums to display
    $fieldsQuery = implode(", ", $fields);
    foreach($tableHead as $key=>$value) {
        $table .= "<th>$value</th>";
    }

    foreach($tableHead2 as $key=>$value) {
        $table2 .= "<th>$value</th>";
    }
    $table .= "</tr>";
    $table2 .= "</tr>";

    $courseQuery = "select * from Courses where Course = '{$course}'";
    $tas = [];
    $current = [];

    /* Executing query */
    $result = $db_connection->query($courseQuery);
    if (!$result) {
        die("Retrieval failed: ". $db_connection->error);
    } else {
        $num_rows = $result->num_rows;
        if ($num_rows === 0) {
            echo "Empty Table<br>";
        } else {
            $result->data_seek(0);
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $tas = unserialize($row['ApplyingTAs']);
            $current = unserialize($row['AcceptedTAs']);
        }
    }

    /* Query */
    $query = "select {$fieldsQuery} from Applications order by {$sortby}";       
    /* Executing query */
    $result = $db_connection->query($query);
    if (!$result) {
        die("Retrieval failed: ". $db_connection->error);
    } else {
        $showTable = true;
        $num_rows = $result->num_rows;

        if ($num_rows === 0) {
            echo "Empty Table<br>";
        } else {
            for ($row_index = 0; $row_index < $num_rows; $row_index++) {
                $result->data_seek($row_index);
                $row = $result->fetch_array(MYSQLI_ASSOC);

                if (in_array($row['DirectoryID'], $tas)) {
                    $table .= "<tr>";
                    foreach($row as $columKey=>$columValue) {
                        /*
                        if ($columKey == "coursesToTA" || $columKey == "previousTaCourses") {
                            $arr = unserialize($columValue);
                            $str = implode(", ", $arr);
                            $table .= "<td>{$str}</td>";
                        } else {
                            $table .= "<td>{$columValue}</td>";
                        }*/
                        $table .= "<td>{$columValue}</td>";
                    }
                    $table .= "<td><input type='submit' class='btn btn-primary' value='resume {$row['DirectoryID']}' name ='resume {$row['DirectoryID']}'></td>";
                    $table .= "<td><input type='submit' class='btn btn-success' value='Add {$row['DirectoryID']}' name = 'Add {$row['DirectoryID']}'></td>";
                    $table .= "</tr>";
                }

                 if (in_array($row['DirectoryID'], $current)) {
                    $table2 .= "<tr>";
                    foreach($row as $columKey=>$columValue) {
                        /*
                        if ($columKey == "coursesToTA" || $columKey == "previousTaCourses") {
                            $arr = unserialize($columValue);
                            $str = implode(", ", $arr);
                            $table .= "<td>{$str}</td>";
                        } else {
                            $table .= "<td>{$columValue}</td>";
                        }*/
                        $table2 .= "<td>{$columValue}</td>";
                    }
                    $table2 .= "<td><input type='submit' class='btn btn-primary' value='resume {$row['DirectoryID']}' name ='resume {$row['DirectoryID']}'></td>";
                    $table2 .= "<td><input type='submit' class='btn btn-success' value='Remove {$row['DirectoryID']}' name = 'Add {$row['DirectoryID']}'></td>";
                    $table2 .= "</tr>";
                }
            }
        }
    }

    $table .= "</table>";
    $table2 .= "</table>";

    $body = $table2."<br><br>".$table;
    echo generatePage($body, "Display Administrative");
?>