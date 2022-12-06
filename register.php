<?php
	require_once "../../config.php";
	
	function register($first_name, $last_name, $code){
		$notice = 0;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt =  $conn->prepare("INSERT INTO register (eesnimi, perenimi, code) VALUES(?,?,?)");
		echo $conn->error;
		$stmt->bind_param("sss", $first_name, $last_name, $code);
		if($stmt->execute()){
			$notice = 1;
		} else {
			$notice = 3;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	function count_registered(){
		$registered_count = 0;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt =  $conn->prepare("SELECT COUNT(id) from register WHERE tühistatud IS NULL");
		echo $conn->error;
        $stmt->bind_result($count_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            $registered_count = $count_from_db;
        }
        $stmt->close();
		$conn->close();
		return $registered_count;
	}
	
	function makstud(){
		$makstuid = 0;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt =  $conn->prepare("SELECT COUNT(id) from register WHERE makstud IS NOT NULL AND tühistatud IS NULL");
		echo $conn->error;
        $stmt->bind_result($count_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            $makstuid = $count_from_db;
        }
        $stmt->close();
		$conn->close();
		return $makstuid;
	}
	
	
	$notice = null;
    $first_name = null;
    $last_name = null;
    $code = null;
	$first_name_error = null;
    $last_name_error = null;
    $code_error = null;
	$registered_count = 0;
	$makstuid = 0;
	
	if($_SERVER["REQUEST_METHOD"] === "POST"){
		if(isset($_POST["user_data_submit"])){
			if(isset($_POST["first_name_input"]) and !empty($_POST["first_name_input"])){
				$first_name = $_POST["first_name_input"];
			} else {
				$first_name_error = "Sisesta eesnimi!";
			}
			if(isset($_POST["last_name_input"]) and !empty($_POST["last_name_input"])){
				$last_name = $_POST["last_name_input"];
			} else {
				$last_name_error = "Sisesta perenimi!";
			}
			if(isset($_POST["code_input"]) and !empty($_POST["code_input"])){
				$code = $_POST["code_input"];
			} else {
				$code_error = "Sisesta üliõpilaskood!";
			}
			
			if(empty($firstname_error) and empty($last_name_error) and empty($code_error)){
				$notice = register($first_name, $last_name, $code);
				if($notice == 1){
					$notice = "Oled peole registreeritud!";
					$first_name = null;
					$last_name = null;
					$code = null;
				} else {
					$notice = "Registreerimine ebaõnnestus!";
				}
			
			}
		}
		
	}
	
	$registered_count = count_registered();
	
	$makstuid = makstud();
	
?>
<!DOCTYPE html>
<html lang="et">
	<head>
	<meta charset="utf-8">

	</head>
	<body>
	<h2>Registreeri peole</h2>
		
		<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		  <label for="first_name_input">Eesnimi:</label><br>
		  <input name="first_name_input" id="first_name_input" type="text" value="<?php echo $first_name; ?>"><span><?php echo $first_name_error; ?></span><br>
		  <label for="lastname_input">Perekonnanimi:</label><br>
		  <input name="last_name_input" id="last_name_input" type="text" value="<?php echo $last_name; ?>"><span><?php echo $last_name_error; ?></span>
		  <br>
		  <label for="code_input">Üliõpilaskood:</label><br>
		  <input type="code" name="code_input" id="code_input" value="<?php echo $code; ?>"><span><?php echo $code_error; ?></span><br>
		  <input name="user_data_submit" type="submit" value="Registreeri"><span><?php echo $notice; ?></span>
		</form>
		
	<hr>
	<p>Registreerunuid: <?php echo $registered_count; ?></p>
	<p>Makstud: <?php echo $makstuid; ?></p>
	
	<p><a href = "cancel.php">Tühista registreerimine</a></p>
	</body>
</html>
	