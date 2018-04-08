<?php
/**
 * Created by PhpStorm.
 * User: Azaah
 * Date: 4/8/2018
 * Time: 7:04 AM
 */

    require_once("support.php");
    require_once("courses.php");
    session_start();

    $body1 = <<<EOBODY
        <form action="{$_SERVER["PHP_SELF"]}" method="post" class="container-fluid">
            <br><img src="umdLogo.gif" alt="UMD logo"/><br>
            <hr style="height:1px;border:none;color:#333;background-color:#333;" />
            <h1>Create Semester</h1><br>
            
            <input type="submit" class="btn btn-primary" name="Auto" value="Auto Accept Applications"/>
            <input type="submit" class="btn btn-primary" name="Manual" value="Manually Accept Applications"/>
            <input type="submit" class="btn btn-primary" name="ViewAll" value="View All Applications"/>
            <input type="submit" class="btn btn-primary" name="Add" value="Add Semester"/><br>
            
            <br><hr style="height:1px;border:none;color:#333;background-color:#333;"/>
            <div style="text-align:left"> If you have any question about our program, please contact the system administrator at
                <a style="text-align:center" href="mailto:your address">admin@terpmail.edu.umd</a>
            </div>
        </form>

EOBODY;

    if  (isset($_POST["Add"])) {
        header("Location: addSemester.php");
    }
    echo generatePage($body1, "Create Semester");
?>