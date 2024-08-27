<?php 
	require_once ('database.php');

	// CREATE DATABASE
	try {
		// Connect to Mysql server
		$db = new PDO($DB_DSN_LIGHT, $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "CREATE DATABASE `".$DB_NAME."`";
		$db->exec($sql);
		echo "<h3>Dabase 'camagru' created successfully</h3><br>";
	} catch (PDOException $e) {
		echo "<h3>ERROR CREATING DB: </h3><br>".$e->getMessage()."<br><h3>Aborting process</h3>";
		exit(-1);
	}

	// CREATE TABLE USERS
	try {
		// Connect to DATABASE previously created
		$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "CREATE TABLE `users` (
			`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`username` VARCHAR(50) NOT NULL,
			`email` VARCHAR(100) NOT NULL,
			`password` VARCHAR(255) NOT NULL,
			`token` VARCHAR(100) NOT NULL,
			`verified` VARCHAR(1) NOT NULL DEFAULT 'N',
			`preference` VARCHAR(3) NOT NULL DEFAULT 'ON',
			`join_date` TIMESTAMP
		)";
		$db->exec($sql);
		echo "<h3>Table 'users' created successfully</h3><br>";
	} catch (PDOException $e) {
		echo "<h3>ERROR CREATING TABLE: </h3><br>".$e->getMessage()."<br><h3>Aborting process</h3>";
	}


	// CREATE TABLE PROFILEIMG
	try {
		$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "CREATE TABLE `profileimg` (
			`id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
			`userid` INT(11) NOT NULL,
			`status` INT(11) NOT NULL
		)";
		$db->exec($sql);
		echo "<h3>Table 'profileimg' created successfully</h3><br>";
	} catch (PDOException $e) {
		echo "<h3>ERROR CREATING TABLE: </h3><br>".$e->getMessage()."<br><h3>Aborting process</h3>";
	}

	// CREATE TABLE GALLERY
	try{
		// Connect to DATABASE previously created
		$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "CREATE TABLE `gallery` (
			`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`userid` LONGTEXT NOT NULL,
			`img` LONGTEXT NOT NULL
		)";
		$db->exec($sql);
		echo "<h3>Table 'gallery' created successfully</h3><br>";
	} catch (PDOException $e) {
		echo "<h3>ERROR CREATING TABLE: </h3><br>".$e->getMessage()."<br><h3>Aborting process</h3>";
	}

	// CREATE TABLE LIKE
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

	// CREATE TABLE COMMENT
	try {
		// Connect to DATABASE previously created
		$dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "CREATE TABLE `comments` (
			`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`userid` VARCHAR(128) NOT NULL,
			`imgid` LONGTEXT NOT NULL,
			`date` datetime NOT NULL,
			`comment` LONGBLOB NOT NULL
		)";
		$dbh->exec($sql);
		echo "<h3>Table 'comments' created successfully</h3><br>";
	} catch (PDOException $e) {
		echo "<h3>ERROR CREATING TABLE: </h3><br>".$e->getMessage()."<br><h3>Aborting process</h3>";
	}

	header("Refresh: 3; url=../index.php");
?>