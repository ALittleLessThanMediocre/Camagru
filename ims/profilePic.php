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
					$fileName = "uploads/profile".$id."*";
					$fileInfo = glob($fileName);
					$fileExt = explode(".", $fileInfo[0]);
					$fileActualExt = $fileExt[1];

					if ($rowImg['status'] == 0) {
						echo "<img style='border-radius: 50%;' width='100px;' height='100px;' src='uploads/profile".$id.".".$fileActualExt."?".mt_rand()."'>";
					} else {
						echo "<img style='border: 2px solid black; border-radius: 50%;' width='100px;' height='100px;' src='uploads/profileDefault.jpg'>";
					}
				echo "</div>";
			}
		}
	}
?>