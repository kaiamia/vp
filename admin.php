<?php
	require_once "../../config.php";
	
	//tühistamise rippmenüü
	$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
	$conn->set_charset("utf8");
	$query = "SELECT code FROM register WHERE tühistatud IS NULL AND makstud IS NULL";
	$result = $conn->query($query);
	if($result->num_rows> 0){
		$options = mysqli_fetch_all($result, MYSQLI_ASSOC);
	}
	
	function kinnitus($code){
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt =  $conn->prepare("UPDATE register SET makstud = now() WHERE code = ?");
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
	$notice = null;
	if($_SERVER["REQUEST_METHOD"] === "POST"){
		if(isset($_POST["code_submit"])){
			if(isset($_POST["registered"]) and !empty($_POST["registered"])){
				$code = $_POST["registered"];
				$notice = kinnitus($code);
				if($notice == 1){
					$notice = "Maksmine edukalt registreeritud!";
					$code = null;
				}
			} 
		}
	}
		//kas on maksnud funktsioon
	$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
	$conn->set_charset("utf8");
	$stmt = $conn->prepare("SELECT eesnimi, perenimi, makstud FROM register WHERE tühistatud IS NULL");
	echo $conn->error;
	$stmt->bind_result($first_name, $last_name, $makstud);
	$stmt->execute();
	$reg_html = null;
	while($stmt->fetch()){
		if(isset($makstud)){
			$makstud = "Jah";
		} else {
			$makstud = "Ei";
		}
		$reg_html .= "<h3>" .$first_name ." " .$last_name ."</h3> \n";
		$reg_html .= "<p>Makstud: " .$makstud ."</p>";
		$reg_html .= "<hr>";
	}
	echo $stmt->error;
	$stmt->close();
	$conn->close();
?>
<!DOCTYPE html>
<html lang="et">
	<head>
	<meta charset="utf-8">

	</head>
	<body>
	<h2>Kinnita maksmine:<h2>
	
	<form method="POST">
		<select name= "registered">
		  <option>Vali üliõpilaskood</option>
		  <?php
		  foreach ($options as $option) {
		  ?>
		    <option><?php echo $option['code']; ?> </option>
		  <?php
		  }
		  ?>
		</select>
		<input type="submit" id="code_submit" name="code_submit" value="OK"><span><?php echo $notice; ?></span>
	</form>
	<hr>
	<h2>Peole registreerunud:</h2>
	<hr>
	<?php echo $reg_html; ?>
	
	</body>
</html>