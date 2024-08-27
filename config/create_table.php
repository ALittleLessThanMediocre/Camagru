<?php 
	require_once("database.php");
	
	try {
		// Connect to DATABASE previously created
		$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "CREATE TABLE `likes` (
			`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`userid` LONGTEXT NOT NULL,
			`galleryid` LONGTEXT NOT NULL
			-- `type` VARCHAR(1) NOT NULL
		)";
		$db->exec($sql);
		echo "<h3>Table 'likes' created successfully</h3><br>";
	} catch (PDOException $e) {
		echo "<h3>ERROR CREATING TABLE: </h3><br>".$e->getMessage()."<br><h3>Aborting process</h3>";
	}
?>