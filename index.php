<?php include_once ("ums/session.php"); ?>
<?php include_once ("indexConnect.php"); ?>

<!DOCTYPE html>
<html>
	<head lang="en">
		<meta charset="utf-8">
		<title>Homepage</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="style/nav_bar.css">
		<link rel="stylesheet" type="text/css" href="style/gallery.style.css">
		<style type="text/css">
			
			a {
				text-decoration: none;
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
			  <a href="index.php">Home</a>
			  <div class="dropdown">
			  	<?php if (isset($_SESSION['id'])): ?>
			    <button class="dropbtn">Menu 
			      <i class="fa fa-caret-down"></i>
			    </button>
			    <div class="dropdown-content">
			      <a href="ums/logout.php">Logout</a>
			      <a href="ims/privateGallery.php">My Gallery</a>
			      <a href="ums/updateProfile.php">Update Profile</a>
			      <a href="ums/changePassword.php">Change Password</a>
			      <a href="ims/photoBooth.php">Photo Booth</a>
			    </div>
				<?php endif ?>
			  </div> 
			</div>
		</header>

		<h2>Camagru</h2>
		

		<?php if (!isset($_SESSION['username'])): ?>
		<p>
			You are currently not signed in <br><br>
		
			Already a user <button><a href="ums/login.php">LOGIN</a></button><br><br>

			Ready to signup <button><a href="ums/signup.php">SIGNUP</a></button>
			<?php include_once ("ims/gallery.php"); ?>
			
		</p><hr>

		<?php else: ?>
		
		<?php $id = $_SESSION['id']; ?>

		<?php 
			if ((time() - $_SESSION['timeStamp']) > 1440) {
				header("Location: ums/logout.php");
			} else {
				$_SESSION['timeStamp'] = time();
			}
		?>
		
		<?php include_once ("ims/profilePic.php") ?>

		<hr>

		<p>You are logged in as <?php if (isset($_SESSION['username'])) echo $_SESSION['username']; ?> </p>
		<hr>
		
		<?php include_once ("ims/gallery.php"); ?>
		<?php endif ?>
	</body>
</html>