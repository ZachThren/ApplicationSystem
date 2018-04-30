<?php


require_once "support.php";
require_once "dblogin.php";

// connecting to database;


function showTranscript(id){
  $id = id;

  $db = connectToDB($dbhost, $dbuser, $dbpassword, $database);
  $sqlQuery = "select Transcript, docMimeType from $table where Directory_ID= '$id'";
  $result = mysqli_query($db, $sqlQuery);
  	if ($result) {
  		$recordArray = mysqli_fetch_assoc($result);
  		header("Content-type: "."{$recordArray['docMimeType']}");
  		echo $recordArray['docData'];
  		mysqli_free_result($result);
  	} else { 				   ;
  		$body = "<h3>Failed to retrieve transscript: ".mysqli_error($db)." </h3>";
  	}


  
}

?>
