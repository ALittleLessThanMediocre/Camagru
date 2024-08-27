<?php 
	include_once("session.php");
	include_once("connect.php");
	include_once("utils.php");

	if (isset($_POST['loginBtn'])) {
			
		// collect form data
		$user = htmlentities($_POST['username']);
		$password = htmlentities($_POST['password']);
		$url = $_SERVER['HTTP_HOST'].str_replace("login.php", "", $_SERVER['REQUEST_URI']);
		// check if user exits in the database;
		try {
			$sqlQuery = $db->prepare("SELECT * FROM users WHERE username = :username");
			$sqlQuery->execute(array(':username' => $user));

		} catch (PDOException $e) {
			echo "An error occurred: ".$e->getMessage();
		}
		
		while ($row = $sqlQuery->fetch()) {
			if (isset($_POST['rememberMe'])) {

				setcookie("username", $_POST['username'], time()+(365*60*24*24));
				setcookie("password", $_POST['password'], time()+(365*60*24*24));
			}

			$id = $row['id'];
			$hashed_password = $row['password'];
			$username = $row['username'];
			$verified = $row['verified'];
			$email = $row['email'];
			$preference = $row['preference'];
			$token = $row['token'];

			if (password_verify($password, $hashed_password)) {
				if ($verified == 'Y'){
					$_SESSION['id'] = $id;
					$_SESSION['username'] = $username;
					$_SESSION['email'] = $email;
					$_SESSION['preference'] = $preference;
					$_SESSION['timeStamp'] = time();
					redirectTo("../index");
				} else {
					$result = flashMessage("Unverified email, please check your inbox");
					sendVerificationEmail($email, $token, $url);
				}
			} else {
				$result = flashMessage("Invalid username or password!");
			}
		} 
	}
?>

<!DOCTYPE html>
<html>
	<head lang="en">
		<meta charset="utf-8">
		<title>Login Page</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="../style/nav_bar.css">

		<style type="text/css">
				
			a {
				text-decoration: none;
				color: black;
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
			  <a href="newForgotPassword.php">Forgot Password</a>
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
		<h2>Camagru</h2>
		<h3>Login</h3>

		<?php if (isset($result)) echo $result; ?>
		
		<form method="POST" action="">
			<table>
				<tr>
					<td>Username:</td> <td><input type="text" name="username" placeholder="Enter Username" value="<?php if (isset($_COOKIE['username'])) { echo $_COOKIE['username'];} ?>" required=""></td>
				</tr>
				<tr>
					<td>Password:</td> <td><input type="password" name="password" placeholder="Enter Password" value="<?php if (isset($_COOKIE['password'])) { echo $_COOKIE['password'];} ?>" required=""></td>
				</tr>
				<tr>
					<td><input type="checkbox" name="rememberMe"></td>
					<td><span class="checkboxtext">Remember me</span></td>
				</tr>
				<tr>
					<td>
					</td> 
					<td>
						<input style="float: right;" type="submit" name="loginBtn" value="Signin">
					</td>
				</tr>
			</table>
		</form>
		<?php include_once ("footer.php"); ?>
	</body>
</html>