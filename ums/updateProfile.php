<?php
	// add our database connection script
	include_once ("connect.php");
	include_once ("utils.php");
	include_once ("session.php");

	if (!$_SESSION['id']) {
		redirectTo("../index");
	
	}

	$id = $_SESSION['id'];
	$form_updates = [];
	$updates = [];

	if (isset($_POST['profileUpdateBtn'])) {
	// process the form if the reset password button is clicked


		$sqlSelect = $db->prepare("SELECT * FROM users WHERE id =:id");
		$sqlSelect->execute(array(':id' => $id));
		$row = $sqlSelect->fetch();

		$rowEmail = $row['email'];
		$rowUsername = $row['username'];

		$preference = htmlentities($_POST['Btn']);
		if (isset($_POST['email']) && !empty($_POST['email'])) {

			// initialize an array to store any error message from the form

			// email validaion / merge the return data into form_errors array
			$form_errors = array_merge($form_errors, checkEmail($_POST));

			if (empty($form_errors)) {

				$email = htmlentities($_POST['email']);

				try {

					$sqlUpdate = $db->prepare("UPDATE users SET email = :email WHERE id = :id");

						// execute the statement
						$sqlUpdate->execute(array(':id' => $id, ':email' => $email));

						$sqlSelect = $db->prepare("SELECT * FROM users WHERE id =:id");
						$sqlSelect->execute(array(':id' => $id));
						$row = $sqlSelect->fetch();

						$rowEmail = $row['email'];

						$form_updates[] = "Email update successful";
						$updates[] = "Email updated";

				} catch (PDOException $e) {
				 	$result = flashMessage("An error occurred: ".$e->getMessage());
				}
			}
		}

		if (isset($_POST['username']) && !empty($_POST['username'])) {

			$username = htmlentities($_POST['username']);

			if (empty($form_errors)) {
				try {

					$sqlUpdate = $db->prepare("UPDATE users SET username = :username WHERE id = :id");

						// execute the statement
						$sqlUpdate->execute(array(':id' => $id, ':username' => $username));

						$form_updates[] = "Username update successful";
						$updates[] = "Username updated";

				} catch (PDOException $e) {
					$result = flashMessage("An error occurred: ".$e->getMessage());
				}
			}
		}

		if (isset($preference) && !empty($preference)) {
			if (empty($form_errors)) {
				if ($preference == "OFF") {
					try {

						$sqlUpdate = $db->prepare("UPDATE users SET preference = :preference WHERE id = :id");

							// execute the statement
							$sqlUpdate->execute(array(':id' => $id, ':preference' => $preference));

							$_SESSION['preference'] = $row['preference'];

							$form_updates[] = "Notification preference update successful";
							$updates[] = "Notification preference updated";

					} catch (PDOException $e) {
						$result = flashMessage("An error occurred: ".$e->getMessage());
					}
				} else if ($preference == "ON") {
					try {

						$sqlUpdate = $db->prepare("UPDATE users SET preference = :preference WHERE id = :id");

							// execute the statement
							$sqlUpdate->execute(array(':id' => $id,':preference' => $preference));
							
							$_SESSION['preference'] = $row['preference'];

							$form_updates[] = "Notification update successful";

							$updates[] = "Notification preference updated";

					} catch (PDOException $e) {
						$result = flashMessage("An error occurred: ".$e->getMessage());
					}
				}
			} 
		} if ($form_updates) {
			sendUpdateEmail($rowEmail, $rowUsername, $updates);
		} if (count($form_errors) == 1) {
			$result = flashMessage("There was 1 error in the form<br>");
		} if (count($form_errors) > 1) {
			$result = flashMessage("There were ".count($form_errors)." errors in the form <br>");
		} if (count($form_updates) == 1) {

			$result = flashMessage("There was 1 update<br>", "Pass");
		} if (count($form_updates) > 1) {

			$result = flashMessage("There were ".count($form_updates)." updates", "Pass");
		} else {
			echo "";
		}
	}
?>

<!DOCTYPE html>
<html>
	<head lang="en">
		<meta charset="utf-8">
		<meta name="viewport" content="width=devide-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="../style/nav_bar.css">
		<style type="text/css">
			.footer {
			  position: relative;
			  left: 0;
			  bottom: 0;
			}
		</style>
		
	<title>Profile Upate Page</title>
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
			      <a href="changePassword.php">Change Password</a>
			       <a href="../ims/photoBooth.php">Photo Booth</a>
			    </div>
				<?php endif ?>
			  </div> 
			</div>
		</header>

		<?php 
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

		<?php include_once ("../ims/upload.php"); ?>
		<?php include_once ("../ims/delete.php"); ?>

		<?php  
			if ((time() - $_SESSION['timeStamp']) > 1440) {
				header("Location: ../ums/logout.php");
			} else {
				$_SESSION['timeStamp'] = time();
			}
		?>

		<h2>Camagru</h2><hr>

		<h3>Update Profile</h3>
		
		<?php if (isset($result)) echo $result; ?>
		<?php if (!empty($form_errors)) echo showErrors($form_errors); ?>
		<?php if (!empty($form_updates)) echo showUpdates($form_updates); ?>
		<form method="POST" action="">
			<table>
				<tr>
					<td>Email: </td><td><input type="email" name="email" placeholder="Enter email"></td>
				</tr>
				<tr>
					<td>Username: </td><td><input type="text" name="username" pattern=".{4,}" title="Username must be at least 4 characters" placeholder="Enter username"></td>
				</tr>
				<tr>
					<td>Notification Preference: <?php echo $_SESSION['preference']; ?> </td><td><input type="radio" name="Btn" value="ON">ON <input type="radio" name="Btn" value="OFF">OFF</td>
				</tr>
				<tr>
					<td></td><td><input style="float: right;" type="submit" name="profileUpdateBtn" value="Update Profile"></td>
				</tr>
			</table>
		</form>
		<?php include_once ("footer.php"); ?>
	</body>
</html>