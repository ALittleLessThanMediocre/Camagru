<?php 
	include_once ('../ums/session.php');
	include_once ('../ums/connect.php');

	$id = $_SESSION['id'];

	if (isset($_POST['remove'])) {
		$fileName = "../uploads/profile".$id."*";
		$fileInfo = glob($fileName);
		$fileExt = explode(".", $fileInfo[0]);
		$fileActualExt = $fileExt[3];

		$file = "../uploads/profile".$id.".".$fileActualExt;

		if (!unlink($file)) {
			echo "";
		} else {
			$sql = $db->prepare("UPDATE profileimg SET status=1 WHERE userid=:id");
			$sql->execute(array(':id' => $id));

			header("Location: ".$_SERVER['HTTP_REFERER']);
			exit();
		}
	}
?>

<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<title></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	</head>
	<body>
		<form action="" method="POST">
				<button type="submit" name="remove">REMOVE</button>
		</form>
	</body>
</html>