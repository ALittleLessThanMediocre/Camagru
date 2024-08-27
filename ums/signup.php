<?php
	include_once("connect.php");
	include_once("utils.php");
	include_once("session.php");

	if (isset($_SESSION['username'])) {

		header("Location: ../index.php");
	}

	// process the form
	if (isset($_POST['signupBtn'])) {
		//intialize an array to store any error message from the form
		$form_errors = [];

		// email validation / merge the return data into form_error array
		$form_errors = array_merge($form_errors, checkEmail($_POST));

		$email = htmlentities($_POST['email']);
		$username = htmlentities($_POST['username']);
		$password = htmlentities($_POST['password']);
		$confirmpw = htmlentities($_POST['confirmpw']);
		$url = $_SERVER['HTTP_HOST'].str_replace("signup.php", "", $_SERVER['REQUEST_URI']);

		$form_errors = array_merge($form_errors, checkUsername($username));

		$form_errors = array_merge($form_errors, checkPassword($password));

		if (checkDuplicateEntries("users", "email", $email, $db)) {

			$form_errors[] = "Email is already taken, please try another one";
		} 
		if (checkDuplicateEntries("users", "username", $username, $db)) {

			$form_errors[] = "Username is already taken, please try another one";
		}

		if ($password != $confirmpw) {
				$result = flashMessage("Your passwords do not match");
		} else {
				//check if error array is empty, if yes process form data and insert record
			if (empty($form_errors)){
				//collect form data and store in variables

				$hashed_password = password_hash($password, PASSWORD_DEFAULT);
				$token = bin2hex(random_bytes(32));

				try {
					
					// use PDO prepared to sanitize data
					$sqlInsert = $db->prepare("INSERT INTO users (`username`, `email`, `password`, `token`, `join_date`)
									VALUES (:username, :email, :password, :token, now())");

					// add the data into the database
					$sqlInsert->execute(array(':username' => $username, ':email' => $email, ':password' => $hashed_password, ':token' => $token));

					$sqlSearch = $db->prepare("SELECT * FROM users WHERE username=:username");
					$sqlSearch->execute(array(':username' => $username));
					$rowSearch = $sqlSearch->fetch();
					$id = $rowSearch['id'];

					$sqlInsert = $db->prepare("INSERT INTO profileimg (userid, status) VALUES (:id, 1)");

					$sqlInsert->execute(array(':id' => $id));

					// check if one new row was created
					if ($sqlInsert->rowCount() == 1){
						$result = flashMessage("Registeration Successful!<br> Please check your inbox to verify account", "Pass");
					}

					sendVerificationEmail ($email, $token, $url);

				} catch (PDOException $e) {
					echo "An error occurred: ".$e->getMessage();
				}
			} else {
				if (count($form_errors) == 1){
					$result = flashMessage("There was 1 error in the form<br>");
				} else {
					$result = flashMessage("There were ".count($form_errors). " errors in the form <br>");
				}
			}
		}
	 }
?>

<!DOCTYPE html>
<html>
	<head lang="en">
		<meta charset="utf-8">
		<title>Registeration Page</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="../style/nav_bar.css">

		<style type="text/css">
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
		
		<h3>Registeration</h3>

		<?php if (isset($result)) echo $result; ?>
		<?php if (!empty($form_errors)) echo showErrors($form_errors); ?>

		<form method="POST" action="">
			<table>
				<tr>
					<td>Email: </td><td><input type="email" name="email" placeholder="Enter Email" required=""></td>
				</tr>
				<tr>
					<td>Username: </td> <td><input type="text" name="username" placeholder="Enter Username" required=""></td>
				</tr>
				<tr>
					<td>Password: </td> <td><input type="password" name="password" placeholder="Enter Password" required=""></td>
				</tr>
				<tr>
					<td>Confirm Password: </td> <td><input type="password" name="confirmpw" placeholder="Confirm Password" required=""></td>
				</tr>
				<tr>
					<td></td> <td><input style="float: right" type="submit" name="signupBtn" value="Signup"></td>
				</tr>
			</table>
		</form>
		<?php include_once ("footer.php"); ?>
	</body>
</html>