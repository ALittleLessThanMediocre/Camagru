<?php 
	include_once ('../ums/connect.php');
	include_once ('../ums/session.php');

	$username = $_SESSION['username'];

	if (isset($_POST['submit']) && ($_FILES['file']['size'] > 0)) {

		$newFileName = $_POST['fileName'];
		if (empty($_POST['fileName'])) {
			$newFileName = "photo";
		} else {
			$newFileName = strtolower(str_replace(" ", "-", $newFileName));
		}
		for ($x=0; $x < count($_FILES['file']['name']); $x++) {

			$fileName = $_FILES['file']['name'][$x];
			$fileTmpName = $_FILES['file']['tmp_name'][$x];
			$fileSize = $_FILES['file']['size'][$x];
			$fileError = $_FILES['file']['error'][$x];
			$fileType = $_FILES['file']['type'][$x];

			$fileExt = explode('.', $fileName);
			$fileActualExt = strtolower(end($fileExt));

			$allowed = ['jpg', 'jpeg', 'png', 'gif'];

			if (in_array($fileActualExt, $allowed)) {
				if ($fileError === 0) {

					$imageFullName = $newFileName.".".uniqid('', true).".".$fileActualExt;
					$fileDestination = '../uploads/gallery/'.$imageFullName;

					try {
						$sqlSelect = $db->prepare("SELECT * FROM gallery");
						$sqlSelect->execute();
						$row = $sqlSelect->fetch();
							
						$sql = $db->prepare("INSERT INTO gallery (userid, img) VALUES (:userid, :img)");

						$sql->execute(array(':userid' => $username, ':img' => $imageFullName));

					} catch (PDOException $e) {
						echo $e->getMessage();
					}
				} 

				move_uploaded_file($fileTmpName, $fileDestination);
				echo $imageFullName." uploaded successfully";
			}
		}
		header("Location: ".$_SERVER['HTTP_REFERER']."?upload=successful");
	} else {
		header("Location: ".$_SERVER['HTTP_REFERER']."upload=empty(var)");
	}
?>