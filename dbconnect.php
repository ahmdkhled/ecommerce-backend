<?php

	require_once('credentials.php');

	$dbconnect=new mysqli(SERVER_NAME,SERVER_USER,SERVER_PASSWORD,DB_NAME);

	if ($dbconnect->connect_error) {
	    die("there is problem in connection".$dbconnect->connect_error);
	}

 ?>
