<?php 
	// add our database connection script
	include_once ("connect.php");
	include_once ("utils.php");
	include_once ("session.php");

	$email = htmlentities($_POST['email']);

	if (isset($_POST['passwordForgotBtn'])) {

		$sqlQuery = $db->prepare("SELECT * FROM users WHERE email=:email");
		$sqlQuery->execute(array(':email' => $email));

		$row = $sqlQuery->fetch();

		if ($sqlQuery->rowCount() == 1) {

		$username = $row['username'];
		$token = $row['token'];
		$verified = $row['verified'];
		$url = $_SERVER['HTTP_HOST'].str_replace("newForgotPassword.php", "", $_SERVER['REQUEST_URI']);

			if ($verified == 'Y') {
				
				$result = flashMessage("Please check your inbox", "Pass");

				sendForgotEmail($email, $row['username'], $token, $url);
				
			} else {

				$result = flashMessage("Unverified email, please check your inbox");
				
				sendVerificationEmail($email, $token, $url);
			}

		} else {

			$result = flashMessage("We don't have that email on our system, please try another one");
		}
	}

?>

<!DOCTYPE html>
<html>
	<head lang="en">
		<meta charset="utf-8">
		<title>Forgot Password Page</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="../style/nav_bar.css">
		<style type="text/css">
			.footer {
			  margin-top: 20px;
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
			  <a href="login.php">Login</a>
			  <div class="dropdown">
			  	<?php if (isset($_SESSION['id'])): ?>
			    <button class="dropbtn">Menu 
			      <i class="fa fa-caret-down"></i>
			    </button>
			    <div class="dropdown-content">
			      <a href="../ums/logout.php">Logout</a>
			      <a href="privateGallery.php">My Gallery</a>
			      <a href="../ums/updateProfile.php">Update Profile</a>
			       <a href="../ims/photoBooth.php">Photo Booth</a>
			    </div>
				<?php endif ?>
			  </div> 
			</div>
		</header> 

		<h2>Camagru</h2><hr>

		<h3>Forgot Password</h3>

		<?php if (isset($result)) echo $result; ?>

		<form method="POST" action="">
			<table>
				<tr>
					<td>Email: </td><td><input type="email" name="email" placeholder="Enter email" required=""></td>
				</tr>
				<tr>
					<td></td><td><input style="float: right;" type="submit" name="passwordForgotBtn" value="Forgot Password"></td>
				</tr>
			</table>
		</form>
		<?php include_once ("footer.php"); ?>
	</body>
</html>