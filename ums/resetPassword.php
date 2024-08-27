<?php
	// add our database connection script
	include_once ("connect.php");
	include_once ("utils.php");
	include_once ("session.php");

	if (!$_GET['token']) {

		redirectTo("../index");
	}
	$getToken = htmlentities($_GET['token']);
	
	if (!empty($getToken)) {
		// process the form if the reset password button is clicked
		if (isset($_POST['changePasswordBtn'])) {
			
			// initialize an array to store any error message from the form
			$form_errors = [];

			$form_errors = array_merge($form_errors, checkPassword($_POST['new_password']));
			// check if error array is empty, if yes process form data and insert record
			if (empty($form_errors)) {
				// collect form data and store in variables
			
				//$email = $_POST['email'];
				$newpw = htmlentities($_POST['new_password']);
				$confirmpw = htmlentities($_POST['confirm_password']);

				// check if new password and confirm password are same
				if ($newpw != $confirmpw) {
					$result = flashMessage("Your passwords do not match");

				} else {
					try {
						$hashed_password = password_hash($newpw, PASSWORD_DEFAULT);

							// use PDO prepare to sanitize SQL statement
							$sqlUpdate = $db->prepare("UPDATE users SET password = :password WHERE token = :token");

							// execute the statement
							$sqlUpdate->execute(array(':password' => $hashed_password, ':token' => $getToken));

							$result = flashMessage("Password Reset Successful!", "Pass");

							$sqlQuery = $db->prepare("SELECT * FROM `users` WHERE token = :getToken");
							$sqlQuery->execute(array(':getToken' => $getToken));

							if (!$row = $sqlQuery->fetch()){
								$result = flashMessage("Invalid Token!");
							}
		

						if ($sqlQuery->rowCount() == 1) {

							$rowToken = $row['token'];

							if ($getToken == $rowToken) {
					
								try {
									$newToken = bin2hex(random_bytes(32));
									$query = $db->prepare("UPDATE users SET token=:newToken WHERE token=:getToken");
									
									$query->execute(array(':newToken' => $newToken, ':getToken' => $getToken));
								} catch (PDOException $e) {
									echo "An error occurred: ".$e->getMessage();
								}
							} else {
								$result = flashMessage("Invalid Token!");
							}
						}

					} catch (PDOException $e) {
						$result = flashMessage("An error occurred: ".$e->getMessage());
					}
				}
			} else {
				if (count($form_errors) == 1) {
					$result = flashMessage("There was 1 error in the form<br>");
				} else {
					$result = flashMessage("There were ".count($form_errors)." errors in the form <br>");
				}
			}
		}
	}
?>

<!DOCTYPE html>
<html>
	<head lang="en">
		<meta charset="utf-8">
		<title>Change Password Page</title>
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

		<h3>Reset password</h3>

		<?php if (isset($result)) echo $result; ?>
		<?php if (!empty($form_errors)) echo show_errors($form_errors); ?>
		<form method="POST" action="">
			<table>
				<tr>
					<td>New Password: </td><td><input type="password" name="new_password" placeholder="Enter new password" required=""></td>
				</tr>
				<tr>
					<td>Confirm Password: </td><td><input type="password" name="confirm_password" placeholder="Confirm password" required=""></td>
				</tr>
				<tr>
					<td></td><td><input style="float: right;" type="submit" name="changePasswordBtn" value="Change Password"></td>
				</tr>
			</table>
		</form>
		<?php include_once ("footer.php"); ?>
	</body>
</html>