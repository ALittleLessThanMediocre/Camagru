<?php 
	include_once ('session.php');
	include_once ('../config/database.php');
	include_once ('utils.php');
	include_once ('connect.php');

	if (!isset($_GET['token'])) {
		redirectTo("../index");
	}

	if (isset($_GET['token']) && !empty($_GET['token'])) {

		$token = htmlentities($_GET['token']);

		$sqlQuery = $db->prepare("SELECT id FROM users WHERE token='".$token."' AND verified='N'");
		$sqlQuery->execute();
		$row = $sqlQuery->fetch();

		if ($sqlQuery->rowCount() == 1) {

			$query = $db->prepare("UPDATE users SET verified='Y' WHERE id = :id");
			$query->execute(array('id' => $row['id']));
			$result = flashMessage("Your acount has been verified, you can now login", "Pass");
		} else {
			$result = flashMessage("The url is either invalid or you already verified your account.");
		}
	}
?>

<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<title>Meme(Me) - VERIFY</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	<body>
		<div>VERIFY</div>
		
		<?php if(isset($result)) echo $result; ?>
		<?php header("Refresh: 3; url=../index.php"); ?>
		<?php include_once ('footer.php'); ?>
	</body>
</html>