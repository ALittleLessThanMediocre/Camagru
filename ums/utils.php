<?php 
	function checkEmail($data) {
		// initialize an array to store error messages
		$form_errors = [];
		$key = 'email';

		// check if the key email exists in the data array
		if (array_key_exists($key, $data)) {
			// check if the email field has a value

			if ($_POST[$key] != NULL) {
				// Remove all illegal characters from email
				$key = filter_var($key, FILTER_SANITIZE_EMAIL);

				// check if input is a valid email address
				if (filter_var($_POST[$key], FILTER_VALIDATE_EMAIL) === false) {
					$form_errors[] = "Invalid email address";
				}
			}
		}

		return ($form_errors);
	}

	function checkUsername ($username) {

		$form_errors = [];

		if (!preg_match('/.{4,}/', $username)) {

			$form_errors[] = "Username must be at least 4 characters long";
		}

		return ($form_errors);
	}

	function checkPassword($password) {

		$form_errors = [];

		if (!preg_match('/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/', $password)) {

			$form_errors[] = "Password insecure, must contain at least 1 digit, 1 uppercase and lowercase letter, and be at least 8 characters long";
		}

		return ($form_errors);
	}

	function showErrors($form_errors_array) {

		$errors = "<p><ul style='color: red;'>";

		// loop through error array and display all items in a list
		foreach ($form_errors_array as $the_error) {
			
			$errors .= "<li>{$the_error}</li>";
		}

		$errors .= "</ul></p>";

		return ($errors);
	}

	function showUpdates($form_updates_array) {

		$updates = "<p><ul style='color: green;'>";

		// loop through error array and display all items in a list
		foreach ($form_updates_array as $the_update) {
			
			$updates .= "<li>{$the_update}</li>";
		}

		$updates .= "</ul></p>";

		return ($updates);
	}

	function flashMessage ($message, $passOrFail = "Fail") {
		if ($passOrFail === "Pass") {

			$data = "<p style='padding: 20px; border: 1px solid grey; color: green;'>{$message}</p>";
		} else {

			$data = "<p style='padding: 20px; border: 1px solid grey; color: red;'>{$message}</p>";
		}

		return ($data);
	}

	function redirectTo ($page) {
		header("Location: {$page}.php");
	}


	function checkDuplicateEntries ($table, $column_name, $value, $db) {

		try{
			$sqlQuery = "SELECT * FROM " .$table." WHERE ".$column_name."=:".$column_name;
			$statement = $db->prepare($sqlQuery);
			$statement->execute(array(":".$column_name => $value));

			if ($row = $statement->fetch()) {

				return true;
			}

			return false;
		} catch (PDOException $e) {
			// handle exception
		}
	}

	function sendVerificationEmail ($email, $token, $url) {
	
		$subject = "[Camagru] - Email Verification";

		$headers = 'MIME-Version: 1.0'."\r\n";
		$headers .= 'Content-type: text/html; charset=UTF-8'."\r\n";
		$headers .= 'From: <noreply@[Camagru].com>'."\r\n";

		$message = '
		<html>
			<head>
				<title>'.$subject.'</title>
			</head>
			<body>
				Thanks for registering to Meme(Me).<br>
				To finalize the registration process please click the link below <br><br>

				<a href="http://'.$url.'verify.php?token='.$token.'">Verify my email</a><br><br>

				Alternatively you can input the following url into your browser to be directed to the verify page<br><br>
				http://'.$url.'verify.php?token='.$token.'<br><br>
				
				If this was not you, please ignore this email and the address will not be used.
			</body>
		</html>
		';

		mail($email, $subject, $message, $headers);
	}

	function sendForgotEmail ($email, $username, $token, $url) {

		$subject = "[Camagru] - Reset your password";

		$headers = 'MIME-Version: 1.0'."\r\n";
		$headers .= 'Content-type: text/html; charset=UTF-8'."\r\n";
		$headers .= 'From: <noreply@[Camagru].com>'."\r\n";

		$message = '
		<html>
			<head>
				<title>'.$subject.'</title>
			</head>
			<body>
				Hi there '.$username.' ! <br>
				Please click the link below to reset your password:
				<a href="http://'.$url.'resetPassword.php?token='.$token.'">Reset Password</a><br>
				
				If this was not you, please ignore this email and the address will not be used.
			</body>
		</html>
		';

		mail($email, $subject, $message, $headers);
	}

	function sendUpdateEmail ($email, $username, $updateArray) {

		$subject = "[Camagru] - Profile Upates";

		$headers = 'MIME-Version: 1.0'."\r\n";
		$headers .= 'Content-type: text/html; charset=UTF-8'."\r\n";
		$headers .= 'From: <noreply@[Camagru].com>'."\r\n";

		$message = '
		<html>
			<head>
				<title>'.$subject.'</title>
			</head>
			<body>
				Hi there '.$username.' ! <br>
				Here is a list of some recent changes made to your account: <br><br>'.implode("<br>", $updateArray).'<br><br> Try to remember this, and keep it private!<br>
			</body>
		</html>
		';

		mail($email, $subject, $message, $headers);
	}

	function sendCommentEmail ($email, $username, $uid, $comment) {

		$subject = "[Camagru] - Comment Notification";

		$headers = 'MIME-Version: 1.0'."\r\n";
		$headers .= 'Content-type: text/html; charset=UTF-8'."\r\n";
		$headers .= 'From: <noreply@[Camagru].com>'."\r\n";

		$message = '
		<html>
			<head>
				<title>'.$subject.'</title>
			</head>
			<body>
				Hi there '.$username.' ! <br>
				It looks like '.$uid.' commented on one of your images, this is what they had to say:<br><br>'.$comment.'<br><br>
			</body>
		</html>
		';

		mail($email, $subject, $message, $headers);
	}

	function sendLikeEmail ($email, $username, $uid) {

		$subject = "[Camagru] - Like Notification";

		$headers = 'MIME-Version: 1.0'."\r\n";
		$headers .= 'Content-type: text/html; charset=UTF-8'."\r\n";
		$headers .= 'From: <noreply@[Camagru].com>'."\r\n";

		$message = '
		<html>
			<head>
				<title>'.$subject.'</title>
			</head>
			<body>
				Hi there '.$username.' ! <br>
				It looks like '.$uid.' liked on one of your images, NICE !
			</body>
		</html>
		';

		mail($email, $subject, $message, $headers);
	}
?>