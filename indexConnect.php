<?php 
	 
	$DB_NAME = "camagru";
	$DB_DSN = "mysql:host=localhost; dbname=".$DB_NAME;
	$DB_DSN_LIGHT = "mysql:host=localhost";
	$DB_USER = "matcha_root";
	$DB_PASSWORD = "matcha_root_password";

	try {

		// create an instance of the PDO class with the required parameters
		$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);

		// set PDO error mode to exception
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// display success message
		//echo "Connected to the database"; 

	} catch (PDOException $e) {
		// display error message
		echo "Connection Failed! ".$e->getMessage();
	}
?>