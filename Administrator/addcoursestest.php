<?php
	require_once("support.php");	

	$host = "dbinstance389.cqiva6sltzci.us-east-2.rds.amazonaws.com";
	$user = "dbuser";
	$password = "dragon123";
	$database = "cmsc389n";
	$table = $coursesTable;

	$db = connectToDB($host, $user, $password, $database);
	
	// $fileToInsert = "JimHenson.jpg";
	// $docMimeType = "image/jpeg";
	$name = "cmsc351";
	$Applying = ['test3id'];
	$apply = serialize($Applying);
	$Accepted = [];
	$acc = serialize($Accepted);
	
	$sqlQuery = "insert into $table (Course, ApplyingTAs, AcceptedTAs) values ";
	$sqlQuery .= "('{$name}', '{$apply}', '{$acc}')";

	$result = mysqli_query($db, $sqlQuery);

	if ($result) {
		$body = "<h3>Document has been added to the database.</h3>";
	} else { 				   ;
		$body = "<h3>Failed to add document: ".mysqli_error($db)." </h3>";
	}
		
	/* Closing */
	mysqli_close($db);
	
	echo generatePage($body);

function connectToDB($host, $user, $password, $database) {
	$db = mysqli_connect($host, $user, $password, $database);
	if (mysqli_connect_errno()) {
		echo "Connect failed.\n".mysqli_connect_error();
		exit();
	}
	return $db;
}
?>