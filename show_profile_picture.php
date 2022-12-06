<?php
	$id = null;
	$type = "image/png";
	$output = "pildid/wrong.png";
	
	if(isset($_GET["photo"]) and !empty($_GET["photo"])){
		$id = filter_var($_GET["photo"], FILTER_VALIDATE_INT);
	}
	
	if(!empty($id)){
		require_once "../../config.php";
		$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT filename FROM vp_userprofilepictures WHERE userid = ? AND deleted = NULL ORDER BY id DESC LIMIT 1");
        echo $conn->error;
        $stmt->bind_param("i", $_SESSION["user_id"]);
        $stmt->bind_result($filename_from_db);
        $stmt->execute();
		if($stmt->fetch()){
			$output = $gallery_photo_profile_folder .$filename_from_db;
			$check = getimagesize($output);
			$type = $check["mime"];
		}
		$stmt->close();
		$conn->close();
	}
	
	//väljastan pildi
	header("Content-type: " .$type);
	readfile($output);