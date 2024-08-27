<?php 
	include_once ("../ums/session.php");
	include_once ("../ums/connect.php");

	if ($_GET['img']) {

		try {
			$img = htmlentities($_GET['img']);
			$sql = $db->prepare("SELECT * FROM gallery WHERE img=:img");
			$sql->execute(array(':img' => $img));
			$row = $sql->fetch();
		} catch (PDOException $e) {
			echo "An error occurred: ".$e->getMessage();
		}

		if ($row['userid'] == $_SESSION['username']) {

			try {
				unlink("../uploads/gallery/".$img);
				$query = $db->prepare("DELETE FROM gallery WHERE img=:img");
				$query->execute(array(':img' => $img));

				$sqlQuery = $db->prepare("DELETE FROM likes WHERE galleryid=:img");
				$sqlQuery->execute(array(':img' => $img));

				$delQuery = $db->prepare("DELETE FROM comments WHERE imgid=:img");
				$delQuery->execute(array(':img' => $img));
				header("Location: ../index.php?delete=success");
			} catch (PDOException $e) {
				echo "An error occurred: ".$e->getMessage();
			}

		} else {
			header("Location: ".$_SERVER['HTTP_REFERER']);
		}
	} else {
			header("Location: ".$_SERVER['HTTP_REFERER']);
	}
?>