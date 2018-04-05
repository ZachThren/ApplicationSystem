<?php
    require_once("pager.php");
    require_once("courses.php");
    session_start();

    if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) &&
        $_SERVER['PHP_AUTH_USER'] === "main" && $_SERVER['PHP_AUTH_PW'] === "terps"){

        $body = <<<EOBODY
                <form action="{$_SERVER["PHP_SELF"]}" method="post" class="container-fluid">
                <br><img src="umdLogo.gif" alt="UMD logo"/><br>
                <hr style="height:1px;border:none;color:#333;background-color:#333;" />
                <h1><b>Applications</b></h1><br>
                
                <h4><b>Select Course</b></h4>
                <select name="course" class="form-control">                                       
EOBODY;

        foreach ($courses as $course) {
            $body .= "<option value="."$course".">"."$course"."</option> ";
        }

        $body .= <<<eobody
                </select ><br>
        
                <h4><b>Sort applications by<b></h4>
                <select name="sortby" class="form-control">
                    <option value="uid">UID</option>
                    <option value="name" selected>name</option>
                    <option value="email">email</option>
                    <option value="gpa">gpa</option>
                    <option value="year">year</option>
                    <option value="gender">gender</option>
                </select><br>
                
                <input type="radio" name="graduate" id="graduate" value="graduate"/><label for="graduate">Graduate</label><br />
                <input type="radio" name="graduate" id="undergrad" value="undergrad" /><label for="undergrad">Undergraduate</label><br><br>
                
                <input id="submit" type="submit" name="displayApp" value="Display Applications"/>
                <hr style="height:1px;border:none;color:#333;background-color:#333;" />
                <div style="text-align:left"> If you have any question about our program, please contact the system administrator at
                    <a style="text-align:center" href="mailto:your address" >tche1@terpmail.umd.edu</a>
                </div>
                </form><br>
eobody;


        if  (isset($_POST["displayApp"])) {
            $_SESSION["course"] = $_POST["course"];
            $_SESSION["sortby"] = $_POST["sortby"];
            $_SESSION["undergraduate"] = $_POST["undergrad"];
            $_SESSION["graduate"] = $_POST["graduate"];

            header("Location: adminDisplay.php");
        }
    } else {
        header("WWW-Authenticate: Basic realm=\"Example System\"");
        header("HTTP/1.0 401 Unauthorized");
    }

    echo generatePage($body, "TA Application | Administrative Access");
?>