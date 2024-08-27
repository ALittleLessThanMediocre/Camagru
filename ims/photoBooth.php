<?php include_once ("../ums/session.php"); ?>
<?php include_once ("../ums/connect.php"); ?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>PhotoBooth</title>

		<link rel="stylesheet" type="text/css" href="../style/video.css">
		<link rel="stylesheet" type="text/css" href="../style/nav_bar.css">

		<style type="text/css">
		
			.footer {
			  margin-top: 40px;
			  position: relative;
			  left: 0;
			  bottom: 0;
			}

			#overLay {
				z-index: 
			}

			.list {
				display: flex;
			}

			.list img {
				border: 2px solid black;
				margin: 3px;
				width: 75px;
				height: 75px;
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
			    </div>
				<?php endif ?>
			  </div> 
			</div>
		</header>

		<div class="booth">
			<div>
				<video id="video" width="400" height="300" autoplay></video>
			</div>
			<a href="#" id="capture" class="captureBtn">Take photo</a>
			<?='<input type="file" id="file-input" name="file">'?>
			<div>
				<canvas id="overLay" width="75" height="75" style="position: absolute;"></canvas>
				<canvas id="canvas" width="400" height="300"></canvas>
			</div>
			<div class="stickers">
				<a href="#" id="post" class="postBtn">Post</a>
			<img src="../meme/meme1.png" id="pen" width="75" height="75">
			<img src="../meme/meme2.png" id="dom" width="75" height="75">
			<img src="../meme/meme3.png" id="ball" width="75" height="75">
			<img src="../meme/meme4.png" id="fat" width="75" height="75">
			</div>
			<div class="list">
			<?php if ((time() - $_SESSION['timeStamp']) > 1440) {
				header("Location: ../ums/logout.php");
			} else {
				$_SESSION['timeStamp'] = time();
			} ?>
			<?php 
				$query = $db->prepare("SELECT * FROM gallery WHERE userid = :userid ORDER BY id DESC LIMIT 5");
				$query->execute(array(':userid' => $_SESSION['username']));
				$pattern = "/{$_SESSION['username']}/";
                while($row = $query->fetch()){
                    if(preg_match($pattern, $row['img'])){
                        echo    "<div>
                                    <a href='comment.php?img=".$row['img']."'>
                                        <img src='../uploads/gallery/".$row['img']."'>
                                    </a>
                                </div>";
                    }
                }
			?>
			</div>
		</div>
		<script src="imageCapture.js"></script>
		<?php include_once ("../ums/footer.php"); ?>
	</body>
</html>