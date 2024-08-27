<?php 
	include_once ('../ums/utils.php');
	include_once ('../ums/session.php');
	include_once ('../ums/connect.php');

	$id = $_SESSION['id'];

	if (isset($_POST['submit'])) {

		$fileName = $_FILES['file']['name'];
		$fileTmpName = $_FILES['file']['tmp_name'];
		$fileSize = $_FILES['file']['size'];
		$fileError = $_FILES['file']['error'];
		$fileType = $_FILES['file']['type'];

		$fileExt = explode('.', $fileName);
		$fileActualExt = strtolower(end($fileExt));

		$allowed = ['jpg', 'jpeg', 'png', 'gif'];

		if (in_array($fileActualExt, $allowed)) {
			if ($fileError === 0) {

				$fileNewName = "profile".$id.".".$fileActualExt;
				$fileDestination = '../uploads/'.$fileNewName;
				move_uploaded_file($fileTmpName, $fileDestination);

				$sqlUpdate = $db->prepare("UPDATE profileimg SET status=0 WHERE userid=:id");
				$sqlUpdate->execute(array(':id' => $id));
				header("Location: ".$_SERVER['HTTP_REFERER']);
			} else {
				header("Location: ".$_SERVER['HTTP_REFERER']);
			}
		} else {
			header("Location: ".$_SERVER['HTTP_REFERER']);
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title></title> 
</head>
<body>

	<form action="" method="POST" enctype="multipart/form-data">
			<input type="file" name="file">
			<button type="submit" name="submit">UPLOAD</button>
	</form>
</body>
</html>