<?php
require_once "../../config.php";
// function check_file_type($file){
	// $file_type = 0;
	// $image_check = getimagesize($file);
	// if($image_check !== false){
		// if($image_check["mime"] == "image/jpeg"){
			// $file_type = "jpg";
		// }
		// if($image_check["mime"] == "image/png"){
			// $file_type = "png";
		// }
		// if($image_check["mime"] == "image/gif"){
			// $file_type = "gif";
		// }
	// }
	// return $file_type;
// } 
//klassi
// function create_filename($photo_name_prefix, $file_type){
	// $timestamp = microtime(1) * 10000;
	// return $photo_name_prefix .$timestamp ."." .$file_type;
// }

//klassi
/* function create_image($file, $file_type){
	$temp_image = null;
	if($file_type == "jpg"){
		$temp_image = imagecreatefromjpeg($file);
	}
	if($file_type == "png"){
		$temp_image = imagecreatefrompng($file);
	}
	if($file_type == "gif"){
		$temp_image = imagecreatefromgif($file);
	}
	return $temp_image;
} */

//klassi
/* function resize_photo($temp_photo, $w, $h, $keep_orig_proportion = true){
	$image_w = imagesx($temp_photo);
	$image_h = imagesy($temp_photo);
	$new_w = $w;
	$new_h = $h;
	//uued muutujad, mis on seotud proportsioonide muutmisega, kärpimisega (crop)
	$cut_x = 0;
	$cut_y = 0;
	$cut_size_w = $image_w;
	$cut_size_h = $image_h;
	
	
	if ($keep_orig_proportion){//säilitan originaalproportsioonid
		if($image_w / $w > $image_h / $h){
			$new_h = round($image_h / ($image_w / $w));
		} else {
			$new_w = round($image_w / ($image_h / $h));
		}
	} else { //kui on vaja kindlat suurust, kärpimist
		
		if($w == $h){ //ruudukujuline
			if($image_w > $image_h){
				$cut_size_w = $image_h;
				$cut_x = round(($image_w - $cut_size_w) / 2);
			} else {
				$cut_size_h = $image_w;
				$cut_y = round(($image_h - $cut_size_h) / 2);
			}
		} else {
			if($image_w > $image_h){
				$cut_size_w = $image_h;
				$cut_x = round(($image_w - $cut_size_w) / 2);
			} else {
				$cut_size_h = $image_w;
				$cut_y = round(($image_h - $cut_size_h) / 2);
			}
		}
	}
	
	$temp_image = imagecreatetruecolor($new_w, $new_h);
	//säilitame vajadusel läbipaistvuse (png ja gif piltide jaoks
	imagesavealpha($temp_image, true);
	$trans_color = imagecolorallocatealpha($temp_image, 0, 0, 0, 127);
	imagefill($temp_image, 0, 0, $trans_color);
	//teeme originaalist väiksele koopia
	imagecopyresampled($temp_image, $temp_photo, 0, 0, $cut_x, $cut_y, $new_w, $new_h, $cut_size_w, $cut_size_h);
	return $temp_image;
} */
/*function resize_photo($temp_photo, $normal_photo_max_w, $normal_photo_max_h){
	//originaalpildi suurus
	$image_w = imagesx($temp_photo);
	$image_h = imagesy($temp_photo);
	$new_w = $normal_photo_max_w;
	$new_h = $normal_photo_max_h;
	//säilitan proportsiooni
	if($image_w / $normal_photo_max_w > $image_h / $normal_photo_max_h){
		$new_h = round($image_h / ($image_w / $normal_photo_max_w));
	} else {
		$new_w = round($image_w / ($image_h / $normal_photo_max_h));
	}
	$temp_image = imagecreatetruecolor($new_w, $new_h);
	//mis image objektile, mis objektist võtate, mis koordinaatidele x ja y, mis koordinaatidelt võtta x ja y, kui laialt, kui kõrgelt, kui laialt võtame, kui kõrgelt võtame  
	imagecopyresampled($temp_image, $temp_photo, 0, 0, 0, 0, $new_w, $new_h, $image_w, $image_h);
	return $temp_image;
	
}
function create_thumbnailname($photo_name_prefix, $file_type){
	$timestamp = microtime(1) * 10000;
	return $photo_name_prefix .$timestamp ."thumbnail." .$file_type;
}
function create_thumbnail($temp_image){
	$image_w = imagesx($temp_photo);
	$image_h = imagesy($temp_photo);
	$temp_image = imagecreatetruecolor(100, 100);
	imagecopyresampled($temp_image, $temp_photo, 0, 0, 0, 0, 100, 100, $image_w, $image_h);
	return $temp_image;
}
*/
//klassi
/* function save_photo($image, $target, $file_type){
	$error = null;
	if($file_type == "jpg"){
		if(imagejpeg($image, $target, 95) == false){
			$error = 1;
		}
	}
	if($file_type == "png"){
		if(imagepng($image, $target, 6) == false){
			$error = 1;
		}
	}
	if($file_type == "gif"){
		if(imagegif($image, $target) == false){
			$error = 1;
		}
	}
	return $error;
} */
function store_photo_data($file_name, $alt, $privacy){
	$notice = null;
	$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
	$conn->set_charset("utf8");
	$stmt = $conn->prepare("INSERT INTO vp_photos (userid, filename, alttext, privacy) VALUES (?, ?, ?, ?)");
	echo $conn->error;
	$stmt->bind_param("issi", $_SESSION["user_id"], $file_name, $alt, $privacy);
	if($stmt->execute() == false){
	  $notice = "Pildi andmebaasi salvestamine ebaõnnestus!";
	}
	$stmt->close();
	$conn->close();
	return $notice;
}

function store_profile_photo_data($file_name){
	$notice = null;
	$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
	$conn->set_charset("utf8");
	$stmt = $conn->prepare("INSERT INTO vp_userprofilepictures (userid, filename) VALUES (?, ?)");
	echo $conn->error;
	$stmt->bind_param("is", $_SESSION["user_id"], $file_name);
	$stmt->execute();
	$photo_id = $conn->insert_id;
	$stmt->close();
	$stmt = $conn->prepare("SELECT id FROM vp_userprofiles WHERE userid = ?");
	echo  $conn->error;
	$stmt->bind_param("s", $_SESSION["user_id"]);
	$stmt->bind_result($id_from_db);
	$stmt->execute();
	if($stmt->fetch()){
		//profiil on olemas
		$stmt->close();
		$stmt =  $conn->prepare("UPDATE vp_userprofiles SET picture = ? WHERE userid = ?");
		echo $conn->error;
		$stmt->bind_param("ss", $photo_id, $_SESSION["user_id"]);
		$stmt->execute();
		$stmt->close();
		$conn->close();
		
	} else {
		$stmt->close();
		$stmt =  $conn->prepare("INSERT INTO vp_userprofiles (userid, picture) VALUES(?,?)");
		echo $conn->error;
		$stmt->bind_param("ss", $_SESSION["user_id"], $photo_id);
		$stmt->execute();
		$stmt->close();
		$conn->close();
	}
	return $notice;
}