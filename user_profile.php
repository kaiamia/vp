<?php
	//session_start();
	require_once "../../config.php";
	require_once "Classes/SessionManager.class.php";
	SessionManager::sessionStart("vp", 0, "~kaldkaia/vp/", "greeny.cs.tlu.ee");
	//kontrollin, kas oleme sisse loginud
	if(!isset($_SESSION["user_id"])){
		header("Location: page.php");
		exit();
	}
	
	//logime välja
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: page.php");
		exit();
	}
	require_once "header.php"; 
	require_once "fnc_user.php";
	require_once "fnc_gallery.php";
	$profile_submit_error = null;

	if($_SERVER["REQUEST_METHOD"] === "POST"){
			if(isset($_POST["user_profile_submit"])){
				/*
				if(isset($_POST["user_description"]) and !empty($_POST["user_description"])){
					$user_description = test_input($_POST["user_description"]);
					if($user_description != $_POST["user_description"]){
						$profile_submit_error = 1;
					}
				}
				if(isset($_POST["bg_color_input"]) and !empty($_POST["bg_color_input"])){
					$bg_color_input = test_input($_POST["bg_color_input"]);	
					if(bg_color_input != $_POST["bg_color_input"]){
						$profile_submit_error = 2;
					}
				}
				if(isset($_POST["txt_color_input"]) and !empty($_POST["txt_color_input"])){
					$txt_color_input = test_input($_POST["txt_color_input"]);
					if($txt_color_input != $_POST["txt_color_input"]){
						$profile_submit_error = 3;
					}
				}
				*/
				if(!empty($_POST["user_description"]) and !empty($_POST["bg_color_input"]) and !empty($_POST["txt_color_input"])){
					$profile_submit_error = profile($_POST["user_description"], $_POST["bg_color_input"], $_POST["txt_color_input"]);
					if(empty($$profile_submit_error)){
						$profile_submit_error = "Andmed salvestatud!";
					}
				}
					
			}
	}

	require_once "Classes/Photoupload.class.php";
	require_once "fnc_photo_upload.php";
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST["photo_submit"])){
			if(isset($_FILES["photo_input"]["tmp_name"]) and !empty($_FILES["photo_input"]["tmp_name"])){
				$upload = new Photoupload($_FILES["photo_input"]);
				if(empty($upload->error)){
					$upload->check_file_size($photo_file_size_limit);
				}
				if(empty($upload->error)){
					$upload->create_filename($photo_name_prefix);
				}
				if(empty($upload->error)){
					$upload->resize_photo(300,300);
					$upload->save_photo($gallery_photo_profile_folder .$upload->file_name);
				}
				if(empty($upload->error)){
					$upload->move_original_photo($gallery_photo_profile_folder .$upload->file_name);
				}
				if(empty($upload->error)){
					$photo_error = store_profile_photo_data($upload->file_name);
				}
				if(empty($photo_error) and empty($upload->error)){
					$photo_error = "Pilt edukalt üles laetud!";
				} /*else {
					$photo_error .= $upload->error;
				}*/
				unset($upload);
			} else {
				$photo_error = "Pildifail on valimata!";
			}
			
			
		}			
	}

?>

<!DOCTYPE html>
<html lang="et">
  <head>
  <meta charset="utf-8">
	
  </head>
  <body>
	<ul>
	<li>Logi <a href="?logout=1">välja</a></li>
	<li>Tagasi <a href="home.php">avalehele</a></li>
    </ul>
	<hr>
	<h2>Profiilipilt</h2>
	<div class="gallery">
	<?php echo show_profile_picture(); ?>
	</div>
	<hr>
    <h2>Loo või muuda enda kasutajaprofiili</h2>
	
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ."?id=" .$_SESSION["user_id"];?>">
		<textarea name="user_description" rows="5" cols="51" placeholder="Minu lühikirjeldus"></textarea>
		<br>
		<label for="bg_color_input">Taustavärv:</label>

		<input name="bg_color_input" id="bg_color_input" type="color" value="<?php echo $_SESSION["user_bg_color"]; ?>">
		<label for="txt_color_input">Tekstivärv:</label>
		
		<input name="txt_color_input" id="txt_color_input" type="color" value="<?php echo $_SESSION["user_txt_color"]; ?>">
		<br>
		<input name="user_profile_submit" type="submit" value="Salvesta">
		<span><?php echo $profile_submit_error; ?></span>
	</form>
	<hr>
	<h2>Lae üles profiilipilt</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
		<label for="photo_input">Vali pildifail: </label>
		<input type= "file" name="photo_input" id="photo_input">
		<br>
		<input type="submit" name="photo_submit" id="photo_submit" value="Lae üles">
		<!--<span><?php echo $photo_error; ?></span>-->
	</form>
	
<?php require_once "footer.php"; ?>