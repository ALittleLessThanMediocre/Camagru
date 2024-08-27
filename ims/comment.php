<?php
	include_once ("../ums/connect.php");
	include_once ("../ums/session.php");
	include_once ("../ums/utils.php");
	date_default_timezone_set('Africa/Johannesburg');

	function setComment($db) {
		if (isset($_POST['commentSubmit'])) {
			$uid = htmlentities($_POST['uid']);
			$date = htmlentities($_POST['date']);
			$comment = htmlentities($_POST['comment']);
			$img = htmlentities($_GET['img']);

			try {

				$sql = $db->prepare("INSERT INTO comments (userid, imgid, date, comment) VALUES (:uid, :imgid, :d, :comment)");
				$sql->execute(array(':uid' => $uid, ':imgid' => $img, ':d' => $date, ':comment' => $comment));

				$sqlSelect = $db->prepare("SELECT * FROM gallery WHERE img=:img");
				$sqlSelect->execute(array(':img' => $img));
				$row = $sqlSelect->fetch();
				$rowUserId = $row['userid'];

				$query = $db->prepare("SELECT * FROM users WHERE username=:username");
				$query->execute(array(':username' => $rowUserId));
				$Row = $query->fetch();
				$RowUserName = $Row['username'];
				$RowEmail = $Row['email'];
				$RowPreference = $Row['preference'];

				if ($RowPreference == "ON")
					sendCommentEmail($RowEmail, $RowUserName, $uid, $comment);
			} catch (PDOException $e) {
				echo "An errorr occurred: ".$e->getMessage();
			}
		}
	}

	function getComments($db) {

		$img = htmlentities($_GET['img']);

		$sql = $db->prepare("SELECT * FROM users WHERE id=:id");
	
		$sql->execute(array(':id' => $id));
		
		if ($sql->rowCount() > 0) {
			
			while ($row = $sql->fetch()) {
				
				try {
					$sqlImg = $db->prepare("SELECT * FROM profileimg WHERE userid=:id");
					$sqlImg->execute(array(':id' => $id));
				} catch (PDOException $e) {
						echo "An error occurred: ".$e-getMessage();
				}

				while ($rowImg = $sqlImg->fetch()) {
					
					echo "<br><br><div class='user-container'>";
						$fileName = "../uploads/profile".$id."*";
						$fileInfo = glob($fileName);
						$fileExt = explode(".", $fileInfo[0]);
						$fileActualExt = $fileExt[3];

						if ($rowImg['status'] == 0) {
							echo "<img style='border-radius: 50%;' width='50px;' height='50px;' src='../uploads/profile".$id.".".$fileActualExt."?".mt_rand()."'>";
						} else {
							echo "<img style='border: 2px solid black; border-radius: 50%;' width='100px;' height='100px;' src='../uploads/profileDefault.jpg'>";
						}
					echo "</div>";
				}
			}
		}

		$sqlSelect = $db->prepare("SELECT * FROM comments WHERE imgid=:imgid ORDER BY id DESC");
		$sqlSelect->execute(array(':imgid' => $img));
		
		while ($Row = $sqlSelect->fetch()) {
			echo "<div class='comment-box'><p>";
				echo $Row['userid']."<br>";
				echo $Row['date']."<br><br>";
				echo nl2br($Row['comment']);
			echo "</p></div>";
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Comments Page</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../style/nav_bar.css">

	<style type="text/css">
		
		body {
			background-color: #ddd;
		}

		textarea {
			width: 75%;
			height: 80px;
			background-color: #fff;
			resize: none;
			display:block;
	        margin-left: auto;
	        margin-right: auto;
		}

		button {
			width: 100px;
			height: 30px;
			background-color: #282828;
			border: none;
			color: #fff;
			font-family: arial;
			font-weight: 400;
			cursor: pointer;
			margin-bottom: 60px;
			display:block;
	        margin-left: auto;
	        margin-right: auto;
		}

		.size {
			width: 60%;
			margin-left: 20%;
		}

		a {
			text-decoration: none;
		}

		.comment-box {
			width: 75%;
			padding: 20px;
			margin-bottom: 4px;
			background-color: #fff;
			border-radius: 4px;
			display:block;
	        margin-left: auto;
	        margin-right: auto;
		}

		.comment-box p {
			font-family: arial;
			font-size: 14px;
			line-height: 16px;
			color: #282828;
			font-weight: 100;
		}

		img	{
			width: 60%;
			height: 60%;
			margin: 2px;
			display:block;
	        margin-left: auto;
	        margin-right: auto;
		}
		
		.footer {
		  position: relative;
		  left: 0;
		  bottom: 0;
		}

	</style>

</head>
<body>
<header>
	<div class="navbar">
	  <a href="../index.php">Home</a>
	  <div class="dropdown">
	  	<?php if (isset($_SESSION['id'])): ?>
	    <button class="dropbtn">Menu 
	      <i class="fa fa-caret-down"></i>
	    </button>
	    <div class="dropdown-content">
	      <a href="../ums/logout.php">Logout</a>
	      <a href="privateGallery.php">My Gallery</a>
	      <a href="../ums/updateProfile.php">Update Profile</a>
	      <a href="../ums/changePassword.php">Change Password</a>
	       <a href="photoBooth.php">Photo Booth</a>
	    </div>
		<?php endif ?>
	  </div> 
	</div>
</header>
	
<?php
	
	$img = htmlentities($_GET['img']);
	
	try {
		$sql = $db->prepare("SELECT * FROM gallery WHERE img=:img");
		$sql->execute(array(':img' => $img));
		$Row = $sql->fetch();

		if (isset($Row['img'])) {

			echo '<img src="../uploads/gallery/'.$img.'">';
		} else {
			header("Location: ../index.php");
		}

	} catch (PDOException $e) {
		echo "An error occurred: ".$e->getMessage();
	}

	try {
		$querySelect = $db->prepare("SELECT * FROM likes WHERE galleryid=:img");
		$querySelect->execute(array(':img' => $img));
	} catch (PDOException $e) {
		echo "An error occurred: ".$e->getMessage();
	}

	$like = 0;
	while ($row = $querySelect->fetch()) {
		$like++;
	}
	

	if (!isset($_SESSION['id'])) {
		echo '<div class="size">
			'.$like.'&#x1F44D;
			</div>';
	}

	if (isset($_SESSION['id'])) {
	
		echo "<div>
		<div class='size'>
		<a href='likes.php?img=".$img."'>".$like.'&#x1F44D;'."</a>
		<a style='float: right;' href='deletePic.php?img=".$img."'>&#9760;</a>
		</div>
		<form method='POST' action='".setComment($db)."'>
		<input type='hidden' name='uid' value='".$_SESSION['username']."'>
		<input type='hidden' name='date' value='".date('Y-m-d H:i:s')."'>
		<textarea name='comment' placeholder='Tell them what you really think'></textarea><br>
		<button class='commentBnt' type='submit' name='commentSubmit'>Comment</button>
		</form>
		</div>";
	}

	getComments($db);

	if ((time() - $_SESSION['timeStamp']) > 1440) {
		header("Location: ../ums/logout.php");
	} else {
		$_SESSION['timeStamp'] = time();
	}
?>
<?php include_once ("../ums/footer.php"); ?>
</body>
</html>