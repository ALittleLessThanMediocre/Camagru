<?php 
	require_once("database.php");

	// DROP DATABASE
	try {
		$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "DROP TABLE `likes` ";
		$db->exec($sql);
		echo "<h3>Table dropped successfully<h3><br>";
	} catch (PDOException $e) {
		echo "<h3>ERROR DROPPING TABLE: </h3><br>".$e->getMessage();
	} 
?>