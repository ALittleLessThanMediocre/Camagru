<?php 
	include_once ('../ums/session.php');
	include_once ('../ums/connect.php');
	$id = $_SESSION['id'];

	if (!isset($_SESSION['id'])) {

		header("Location: ../index.php");
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Gallery</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://fonts.googleapis.com/css?family=Catamaran&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="../style/gallery.style.css">
	<link rel="stylesheet" type="text/css" href="../style/nav_bar.css">
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
			  <a href="../index.php">Home</a>
			  <div class="dropdown">
			  	<?php if (isset($_SESSION['id'])): ?>
			    <button class="dropbtn">Menu 
			      <i class="fa fa-caret-down"></i>
			    </button>
			    <div class="dropdown-content">
			      <a href="../ums/logout.php">Logout</a>
			      <a href="../ums/updateProfile.php">Update Profile</a>
			      <a href="../ums/changePassword.php">Change Password</a>
			       <a href="photoBooth.php">Photo Booth</a>
			    </div>
				<?php endif ?>
			  </div> 
			</div>
		</header>
	<main>
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
		<section class="gallery-links">
			<div class="wrapper">
				<h2>Private Gallery</h2>

				<div class="gallery-container">
					<?php
					if (isset($_SESSION['id'])) {
						echo '<div class="gallery-upload">
						<form action="multiUpload.php" method="POST" enctype="multipart/form-data">
							<input type="text" name="fileName" placeholder="File name...">
							<input type="file" name="file[]" multiple>
							<button type="submit" name="submit">UPLOAD</button>
						</form>
					</div>';
					}

					if ((time() - $_SESSION['timeStamp']) > 1440) {
						header("Location: ../ums/logout.php");
					} else {
						$_SESSION['timeStamp'] = time();
					}
					?>

					<?php
						try {

							$query = $db->prepare("SELECT * FROM gallery WHERE userid=:id");
							$query->execute(array(':id' => $_SESSION['username']));
							$total_records = $query->rowCount();
							$records_per_page = 20;
							$page = '';
							if (!isset($_GET['page'])) {
								
								$page = 1;
							} else {
								if (is_numeric($_GET['page'])) {
									$page = $_GET['page'];
								} else {
									$page = 1;
								}
							}

							$total_pages = ceil($total_records/$records_per_page);

							$start_from = ($page-1) * $records_per_page;

							$sql = $db->prepare("SELECT * FROM gallery WHERE userid=:userid ORDER BY id DESC LIMIT $start_from, $records_per_page");
							$sql->execute(array(':userid' => $_SESSION['username']));
							
							while ($row = $sql->fetch()) {
								
								echo '<div class="images">
									<a href="comment.php?img='.$row["img"].'">
									<img src="../uploads/gallery/'.$row["img"].'">
								</a>
								</div>';
							}

							echo "<br>";
							for ($i = 1; $i <= $total_pages; $i++) {
								echo "<a href='privateGallery.php?page=$i'> $i </a>";
							}

						} catch (PDOException $e) {
							echo $e->getMessage();
						}
					?>
				</div>
			</div>
		</section>
	</main>
	<?php include_once ("../ums/footer.php"); ?>
</body>
</html>