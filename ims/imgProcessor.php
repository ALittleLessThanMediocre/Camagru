<?php 
	include_once ("../ums/session.php");
	include_once ("../ums/connect.php");


	$username = $_SESSION['username'];
	$baseImg = $_POST['baseUrl'];
	$sticker = $_POST['memeUrl'];

	var_dump($_POST['memeUrl']);

	if (!empty($baseImg)) {

		$baseImgName = $username.".".uniqid().".png";
		$imgPath = "../uploads/gallery/".$baseImgName;
		$imgUrl = str_replace("data:image/png;base64,", "", $baseImg);
		$imgUrl = str_replace(" ", "+", $imgUrl);
		$imgDecoded = base64_decode($imgUrl);
		file_put_contents($imgPath, $imgDecoded);

		if (isset($sticker) && !empty($sticker)) {

			$overLayImage = "OverLay?".$username."?".uniqid().".png";
			$overLayPath = "../uploads/gallery/".$overLayImage;
			$imgUrl = str_replace("data:image/png;base64,", "", $sticker);
			$imgUrl = str_replace(" ", "+", $imgUrl);
			$imgDecoded = base64_decode($imgUrl);
			file_put_contents($overLayPath, $imgDecoded);
		}

		if (isset($baseImg) && isset($sticker)) {

			$dst = imagecreatefrompng($imgPath);
			$src = imagecreatefrompng($overLayPath);

			imagecopy($dst, $src, 10, 9, 0, 0, 75, 75);
			imagepng($dst, $imgPath);

			imagedestroy($dst);
			imagedestroy($src);
			unlink($overLayPath);
		}

		try{
	        $query = $db->prepare("INSERT INTO gallery (userid, img) VALUES (:userid, :img)");
	        $query->execute(array(':userid' => $_SESSION['username'], ':img' => $baseImgName));

	    } catch (PDOException $e){
	        echo "An error occurred: ".$e->getMessage();
	    }
	}
?>