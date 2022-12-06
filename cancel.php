<?php
	require_once "../../config.php";
	
	function cancel($code){
		$notice = 0;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt =  $conn->prepare("UPDATE register SET tühistatud = now() WHERE code = ?");
		echo $conn->error;
		$stmt->bind_param("s", $code);
		if($stmt->execute()){
			$notice = 1;
		} else {
			$notice = 3;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}
	$code_error = null;
	$notice = null;
	$code = null;
	
	if($_SERVER["REQUEST_METHOD"] === "POST"){
		if(isset($_POST["user_data_submit"])){
			if(isset($_POST["code_input"]) and !empty($_POST["code_input"])){
				$code = $_POST["code_input"];
			} else {
				$code_error = "Sisesta üliõpilaskood!";
			}
			if(empty($code_error)){
				$notice = cancel($code);
				if($notice == 1){
					$notice = "Oled registreerimise tühistanud!";
					$code = null;
				} else {
					$notice = "Registreerimise tühistamine ebaõnnestus!";
				}
			
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
	<h2>Tühista registreerimine</h2>
		<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">	
			<label for="code_input">Üliõpilaskood:</label><br>
			<input type="code" name="code_input" id="code_input" value="<?php echo $code; ?>"><span><?php echo $code_error; ?></span><br>
			<input name="user_data_submit" type="submit" value="Tühista"><span><?php echo $notice; ?></span>
		</form>
	<p><a href = "register.php">Tagasi registreerimise lehele</a></p>
	</body>
</html>