<?php 
	require_once("database.php");

	// DROP DATABASE
	try {
		$db = new PDO($DB_DSN_LIGHT, $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "DROP DATABASE `".$DB_NAME."`";
		$db->exec($sql);
		echo "<h3>Database dropped successfully<h3><br>";
	} catch (PDOException $e) {
		echo "<h3>ERROR DROPPING DB: </h3><br>".$e->getMessage();
	} 
?>