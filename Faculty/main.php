<?php
require_once("supportAS.php");

$body = "<form action='FacultyLogin.php' method='post'>";
$body .= "<input type='submit' name='button'>";
$body .= "</form>";



echo generatePage($body);

 ?>
