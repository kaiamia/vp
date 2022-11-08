<?php
require_once "header.php"; 
require_once "fnc_user.php";

if($_SERVER["REQUEST_METHOD"] === "POST"){
		if(isset($_POST["user_profile_submit"])){
			if(isset($_POST["user_description"]) and !empty($_POST["user_description"])){
				$user_description = test_input($_POST["user_description"]);
				if($user_description != $_POST["user_description"]){
					$user_description_error = 1;
				}
			}
			if(isset($_POST["bg_color_input"]) and !empty($_POST["bg_color_input"])){
				$bg_color_input = test_input($_POST["bg_color_input"]);	
				if(bg_color_input != $_POST["bg_color_input"]){
					$bg_color_input_error = 2;
				}
			}
			if(isset($_POST["txt_color_input"]) and !empty($_POST["txt_color_input"])){
				$txt_color_input = test_input($_POST["txt_color_input"]);
				if($txt_color_input != $_POST["txt_color_input"]){
					$txt_color_input_error = 1;
				}
			}
			if(empty($user_description_error) and empty($bg_color_input_error) and empty($txt_color_input_error)){
				
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
	
	<hr>
    <h2>Loo või muuda enda kasutajaprofiili</h2>
	
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ."?id=" .$_SESSION["user_id"];?>">
		<textarea name="user_description" rows="5" cols="51" placeholder="Minu lühikirjeldus"></textarea>
		<br>
		<label for="bg_color_input">Taustavärv:</label>

		<input name="bg_color_input" id="bg_color_input" type="color" value="<?php echo $bg_color_input; ?>">
		<label for="txt_color_input">Tekstivärv:</label>
		
		<input name="txt_color_input" id="txt_color_input" type="color" value="<?php echo $txt_color_input; ?>">
		<br>
		<input name="user_profile_submit" type="submit" value="Salvesta">
	</form>
	
<?php require_once "footer.php"; ?>