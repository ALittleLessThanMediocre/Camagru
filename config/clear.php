<?php 
	require_once ("database.php");

	// DROP DATABASE
	try {
		$dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "DELETE FROM `comments`";
		$dbh->exec($sql);

		$sql = "DELETE FROM `likes`";
		$dbh->exec($sql);

		$sql = "DELETE FROM `gallery`";
		$dbh->exec($sql);

		array_map('unlink', glob("../view/images/*.png"));
		echo "Images successfully cleared"."\n";
	} catch (PDOException $e) {
		echo "ERROR CLEARING DB: \n".$e->getMessage()."\n";
	}
?>