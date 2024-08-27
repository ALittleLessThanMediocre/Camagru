<?php 
	include_once ("../ums/connect.php");
	include_once ("../ums/session.php");
	include_once ("../ums/utils.php");

	if ($_GET['img']) {
		
		$img = htmlentities($_GET['img']);
		$sql = $db->prepare("SELECT * FROM likes WHERE galleryid=:img");
		$sql->execute(array(':img' => $img));
		$row = $sql->fetch();
		$imageOwner = $row['userid'];

		$query = $db->prepare("SELECT * FROM users WHERE username=:userid");
		$query->execute(array(':userid' => $row['userid']));
		$Row = $query->fetch();
		$RowEmail = $Row['email'];

		if ($sql->rowCount() > 0) {

			try {
				$query = $db->prepare("SELECT * FROM likes WHERE userid=:userid");
				$query->execute(array(':userid' => $_SESSION['username']));
				$Row = $query->fetch();
			} catch (PDOException $e) {
				echo "An error occurred: ".$e->getMessage();
			}
			
			if ($query->rowCount() > 0) {

				try {
					$sqlDelete = $db->prepare("DELETE FROM likes WHERE userid=:userid");
					$sqlDelete->execute(array(':userid' => $_SESSION['username']));
					header('Location: '.$_SERVER['HTTP_REFERER']);
				} catch (PDOException $e) {
					echo "An error occurred: ".$e->getMessage();
				}
			}else {
				$sqlIn = $db->prepare("INSERT INTO likes (userid, galleryid) VALUES (:userid, :galleryid)");
				$sqlIn->execute(array(':userid' => $_SESSION['username'], ':galleryid' => $img));

				if ($Row['preference'] == 'ON') {
					sendLikeEmail($RowEmail, $imageOwner, $_SESSION['username']);
				}

				header('Location: '.$_SERVER['HTTP_REFERER']);
			}
		}else { 
			try {
				$sqlInsert = $db->prepare("INSERT INTO likes (`userid`, `galleryid`) VALUES (:userid, :galleryid)");
				$sqlInsert->execute(array(':userid' => $_SESSION['username'], ':galleryid' => $img));

				if ($Row['preference'] == 'ON') {
					sendLikeEmail($RowEmail, $imageOwner, $_SESSION['username']);
				}
				
				header('Location: '.$_SERVER['HTTP_REFERER']);
			} catch (PDOException $e) {
				echo "An error occurred: ".$e->getMessage();
			}
		}
	}
?>