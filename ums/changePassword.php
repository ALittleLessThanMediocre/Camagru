<?php
	// add our database connection script
	include_once ("connect.php");
	include_once ("utils.php");
	include_once ("session.php");


	if (!$_SESSION['id']) {

		redirectTo("../index");
	}
	$id = $_SESSION['id'];

	if (isset($_POST['changePasswordBtn'])) {

		$sqlSelect = $db->prepare("SELECT * FROM users WHERE id =:id");
		$sqlSelect->execute(array(':id' => $id));
		$row = $sqlSelect->fetch();

		$rowEmail = $row['email'];
		$rowUsername = $row['username'];

		$form_errors = [];
		$updates = [];

		$form_errors = array_merge($form_errors, checkPassword($_POST['new_password']));

		if (empty($form_errors)) {
			
			$newpw = $_POST['new_password'];
			$confirmpw = $_POST['confirm_password'];

			if ($newpw != $confirmpw) {
				$result = flashMessage("Your passwords do not match");

			} else {
				try {
					$hashed_password = password_hash($newpw, PASSWORD_DEFAULT);

						
						$sqlUpdate = $db->prepare("UPDATE users SET password = :password WHERE id = :id");
						$sqlUpdate->execute(array(':password' => $hashed_password, ':id' => $id));

						$result = flashMessage("Password Reset Successful!", "Pass");

						$updates[] = "Password updated";

						sendUpdateEmail($rowEmail, $rowUsername, $updates);

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
			      <a href="logout.php">Logout</a>
			      <a href="../ims/privateGallery.php">My Gallery</a>
			      <a href="updateProfile.php">Update Profile</a>
			       <a href="../ims/photoBooth.php">Photo Booth</a>
			    </div>
				<?php endif ?>
			  </div> 
			</div>
		</header>

		<?php 

			if ((time() - $_SESSION['timeStamp']) > 1440) {
				header("Location: logout.php");
			} else {
				$_SESSION['timeStamp'] = time();
			}

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
								echo "<img style='border-radius: 50%;' width='100px;' height='100px;' src='../uploads/profile".$id.".".$fileActualExt."?".mt_rand()."'>";
							} else {
								echo "<img style='border: 2px solid black; border-radius: 50%;' width='100px;' height='100px;' src='../uploads/profileDefault.jpg'>";
							}
						echo "</div>";
					}
				}
			}
		?>

		<h2>Camagru</h2><hr>

		<h3>Change password</h3>

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