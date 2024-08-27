<?php 
	include_once ('../ums/session.php');
	include_once ('../ums/connect.php');
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Gallery</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://fonts.googleapis.com/css?family=Catamaran&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="style/gallery.style.css">
</head>
<body>
	<main>
		<section class="gallery-links">
			<div class="wrapper">
				<h2>Public Gallery</h2>

				<div class="gallery-container">
					<?php
					if (isset($_SESSION['id'])) {
						echo '<div class="gallery-upload">
						<form action="ims/multiUpload.php" method="POST" enctype="multipart/form-data">
							<input type="text" name="fileName" placeholder="File name...">
							<input type="file" name="file[]" multiple>
							<button type="submit" name="submit">UPLOAD</button><br><br>
						</form>
					</div>';
					}
					?>

					<?php
						$query = $db->prepare("SELECT * FROM gallery");
						$query->execute();
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

						$sql = $db->prepare("SELECT * FROM gallery ORDER BY id DESC LIMIT $start_from, $records_per_page");
						$sql->execute();
						
						while ($row = $sql->fetch()) {
							
							echo '<div class="images">
								<a href="ims/comment.php?img='.$row["img"].'">
								<img src="uploads/gallery/'.$row["img"].'">
							</a>
							</div>';
						}
						echo "<br>";
						for ($i = 1; $i <= $total_pages; $i++) {
							echo "<a href='index.php?page=$i'> $i </a>";
						}
					?>
				</div>
			</div>
		</section>
	</main>
	<?php include_once ("ums/footer.php"); ?>
</body>
</html>