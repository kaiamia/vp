<?php
	//loen sisse konfiguratsioonifaili
	require_once "../../config.php";
	
	$author_name = "Kaia Mia Kalda";
	//echo $author_name;
	$full_time_now = date("d.m.Y H:i:s");
	$weekday_names_et = ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"];
	//echo $weekday_names_et[2];
	$weekday_now = date("N");
	$hour_now = date("H");
	$part_of_day = "suvaline hetk";
	$vanasõnad = ["Enne töö, siis lõbu.", "Suur tükk ajab suu lõhki.", "Üheksa korda mõõda, üks kord lõika.", "Paha siga, mitu viga: küll kärss kärnas, küll maa külmand.", "Pill tuleb pika ilu peale."];
	// == on võrdne, != ei ole võrdne, <, >, <=, >=
	if($weekday_now <= 5){
		if($hour_now >= 22 and $hour_now < 7){
			$part_of_day = "uneaeg";
		}
		if($hour_now == 7 ){
			$part_of_day = "aeg minna kooli!";
		}
		if($hour_now >= 8 and $hour_now < 15){
			$part_of_day = "kooliaeg";
		}
		if($hour_now >= 15 and $hour_now < 18){
			$part_of_day = "vaba aeg";
		}
		if($hour_now >= 18 and $hour_now < 20){
			$part_of_day = "trenn";
		}
		if($hour_now >= 20 and $hour_now < 22){
			$part_of_day = "aeg pesta ja õppida";
		}
	}
	if($weekday_now == 6){
		if($hour_now >= 23 and $hour_now < 10){
			$part_of_day = "uneaeg";
		}
		if($hour_now >= 10 and $hour_now < 23){
			$part_of_day = "vedelemise aeg";
		}
	}
	if($weekday_now == 7){
		if($hour_now >= 23 and $hour_now < 10){
			$part_of_day = "uneaeg";
		}
		if($hour_now >= 10 and $hour_now <12){
			$part_of_day = "hommikusöögi ja trenniks valmistumise aeg";
		}
		if($hour_now >= 12 and $hour_now <20){
			$part_of_day = "trenn";
		}
		if($hour_now >= 20 and $hour_now <23){
			$part_of_day = "aeg pesta ja õppida";
		}
	}
	// and & or |
	//vaatame semestri pikkust ja kulgemist
	$semester_begin = new DateTime("2022-09-05");
	$semester_end = new DateTime("2022-12-18");
	$semester_duration = $semester_begin->diff($semester_end);
	$semester_duration_days = $semester_duration->format("%r%a");
	//echo $semester_duration_days;
	$from_semester_begin = $semester_begin->diff(new DateTime("now"));
	$from_semester_begin_days = $from_semester_begin->format("%r%a");
	
	//loendan massiivi (array) liikmeid
		//echo count($weekday_names_et);
	//juhuslik arv
		//echo mt_rand(1, 9);
	//juhuslik element massiivist
		//echo $weekday_names_et[mt_rand(0, count($weekday_names_et) - 1)];
		
	//loeme fotode kataloogi sisu
	$photo_dir = "photos/";
	//$all_files = scandir($photo_dir);
	//uus_massiiv = array_slice(massiiv, mis kohast alates);
	$all_files = array_slice(scandir($photo_dir),2);
	//var_dump($all_files);
	
	//  <img src="kataloog/fail" alt="tekst">
	$photo_html = null;
	
	//tsükkel
	//kui suureneb/väheneb ühe võrra: ++, --
	/*for($i = 0; $i < count($all_files); $i ++){
		echo $all_files[$i];
	}*/
	/*foreach($all_files as $file_name){
		echo $file_name ." | ";
	}*/
	
	//loetlen lubatud failitüübid (jpg, png)
	// MIME    
	$allowed_photo_types = ["image/jpeg", "image/png"];
	$photo_files = [];
	foreach($all_files as $file_name){
		$file_info = getimagesize($photo_dir .$file_name);
		if(isset($file_info["mime"])){
			if(in_array($file_info["mime"], $allowed_photo_types)){
				array_push($photo_files, $file_name);
			}
		}
	}
	//var_dump($photo_files);
	
	
	//vormi info kasutamine
	//$_POST
	//var_dump($_POST);
	$adjective_html = null;
	if(isset($_POST["todays_adjective_input"]) and !empty($_POST["todays_adjective_input"])){
		$adjective_html = "<p>Tänase kohta on arvatud: " .$_POST["todays_adjective_input"] .".</p>";
	}
	
	//teen fotode rippmenüü
	// <option value="0">tln_1.JPG</option>
	
	$photo_number = mt_rand(0, count($photo_files) - 1);
	
	if(isset($_POST["photo_select"]) and $_POST["photo_select"] >= 0){
		$photo_number = $_POST["photo_select"];
	}
	
	$select_html = '<option value="" selected disabled>Vali pilt</option>';
	for($i = 0; $i < count($photo_files); $i ++){
		$select_html .= '<option value="' .$i .'"';
		if($i == $photo_number){
			$select_html .= " selected";
		}
		$select_html .= ">";
		$select_html .= $photo_files[$i];
		$select_html .= "</option> \n";
		
	}
	$photo_html = '<img src = "' .$photo_dir .$photo_files[$photo_number] .'" alt = "Tallinna pilt">';
	
	$comment_error = null;
	$grade = 7;
	//tegeleme päevale antud hinde ja kommentaariga
	if(isset($_POST["comment_submit"])){
		if(isset($_POST["comment_input"]) and !empty($_POST["comment_input"])){
			$comment = $_POST["comment_input"];
		} else {
			$comment_error = "Kommentaar jäi lisamata!";
		}
		$grade = $_POST["grade_input"];
		
		if(empty($comment_error)){
			//loome andmebaasiühenduse
			$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
			//määrame suhtlemisel kasutatava kooditabeli
			$conn->set_charset("utf8");
			//valmistame ette SQL keeles päringu
			$stmt =  $conn->prepare("INSERT INTO vp_daycomment (comment, grade) VALUES(?,?)");
			echo $conn->error;
			//seome SQL päringu päris andmetega
			//määrata andmetüübid: i - integer (täisarv), d - decimel (murdarv), s - string (tekst)
			$stmt->bind_param("si", $comment, $grade);
			//täidame käsu
			if($stmt->execute()){
				$grade = 7;
			}
			echo $stmt->error;
			//sulgeme käsu/päringu
			$stmt->close();
			//sulgeme andmebaasiühenduse
			$conn->close();
		}
	}
	$email = null;
	$email_error = null;
	$password = null;
    $password_error = null;
	
	if($_SERVER["REQUEST_METHOD"] === "POST"){
		if(isset($_POST["user_data_submit"])){
			
			$email = $_POST["email_input"];
			$password = $_POST["password_input"];
			
			$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
			//määrame suhtlemisel kasutatava kooditabeli
			$conn->set_charset("utf8");
			//valmistame ette SQL keeles päringu
			$stmt =  $conn->prepare("SELECT password FROM vp_users_1 WHERE email = ?");
			echo $conn->error;
			$stmt->bind_param("s", $email);
			$stmt->execute();
			echo $stmt->error;
            $stmt->bind_result($password_from_db);
			
			if($stmt->fetch()){
				if(password_verify($password, $password_from_db)){
				} else {
					$password_error = "Salasõna on ebakorrektne!";
			    }
			} else {
				$email_error = "Kasutajatunnus on ebakorrektne!";
			}
			$stmt->close();
			$conn->close();
			if(empty($email_error) and empty($password_error)){
				header("Location: home.php");
			}  
		}//if submit lõppeb
	}//if POST lõppeb		
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo $author_name; ?>, veebiprogrammeerimine</title>
</head>
<body>
	<img src="pildid/vp_banner_gs.png" alt="Banner">
	<h1><?php echo $author_name; ?>, veebiprogrammeerimine</h1>
	
	<p>See leht on loodud õppetöö raames ja ei sisalda tõsist infot!</p>
	<p>Õppetöö toimus <a href="https://www.tlu.ee">Tallinna Ülikoolis</a>, Digitehnoloogiate instituudis.</p>
	
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label for="email_input">E-mail (kasutajatunnus):</label><br>
	  <input type="email" name="email_input" id="email_input" value="<?php echo $email; ?>"><span><?php echo $email_error; ?></span><br>
	  <label for="password_input">Salasõna (min 8 tähemärki):</label><br>
	  <input name="password_input" id="password_input" type="password"><span><?php echo $password_error; ?></span><br>
	  <input name="user_data_submit" type="submit" value="Submit">
	</form>
	
	<p>Lehe avamise hetk: <?php echo $weekday_names_et[$weekday_now - 1] .", " .$full_time_now; ?>.</p>
	<p>Praegu on <?php echo $part_of_day; ?>.</p>
		
	<p><?php echo $vanasõnad[mt_rand(0, count($vanasõnad) - 1)];?></p>
	
	<p>Semester edeneb: <?php echo $from_semester_begin_days ."/" .$semester_duration_days; ?></p>
	
	<!--siin tuleb väike omadussõnade vorm-->
	<form method="POST">
		<input type="text" id="todays_adjective_input" name="todays_adjective_input"
		placeholder="omadussõna tänase kohta">
		<input type="submit" id="todays_adjective_submit" name="todays_adjective_submit"
		value="Saada omadussõna">
	</form>
	<?php echo $adjective_html; ?>
	<hr>
	
	<!-- päeva kommentaaride lisamise vorm-->
	<form method="POST">
		<label for="comment_input">Kommentaar tänase päeva kohta: </label>
		<br>
		<textarea id="comment_input" name="comment_input" cols="70" rows="2" placeholder="kommentaar"></textarea>
		<br>
		<label for="grade_input">Hinne tänasele päevale (0 ... 10):</label>
		<input type="number" id="grade_input" name="grade_input" min="0" max="10" step="1" value="<?php echo $grade; ?>">
		<br>
		<input type="submit" id="comment_submit" name="comment_submit" value="Salvesta">
		<span><?php echo $comment_error; ?>
	</form>	
	<hr>
	<img src="pildid/tlu_39.jpg" alt="Tallinna Ülikooli õppehoone">
	<hr>
	<!-- teise pildi lisamine-->
	<form method="POST">
		<select id="photo_select" name="photo_select">
			<?php echo $select_html; ?>
		</select>
		<input type="submit" id="photo_submit" name="photo_submit" value="OK">
		
	</form>
	<hr>
	<?php echo $photo_html ?>
	<hr>
</body>
</html>