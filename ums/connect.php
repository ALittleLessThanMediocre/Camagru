<?php 
	require_once('../config/database.php');

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