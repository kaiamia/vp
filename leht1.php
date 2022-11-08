<?php
	require_once "../../config.php";
	session_start();
	if(!isset($_SESSION["user_id"])){
		//jõuga viiakse page.php lehele
		header("Location: page.php");
		exit(); 
	}
	
	//logime välja
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: page.php");
	    exit();
	}
	
	$title = null;
	$year = date("Y");
	$duration = 60;
	$genre = null;
	$studio = null;
	$director = null;
	
	$title_error = null;
	$year_error = null;
	$duration_error = null;
	$genre_error = null;
	$studio_error = null;
	$director_error = null;
	if($_SERVER["REQUEST_METHOD"] === "POST"){
		if(isset($_POST["film_submit"])){
			if(isset($_POST["title_input"]) and !empty($_POST["title_input"])){
				$title = $_POST["title_input"];
			} else {
				$title_error = "Pealkiri jäi lisamata!";
			}
			if(isset($_POST["year_input"]) and !empty($_POST["year_input"])){
				$year = $_POST["year_input"];
			} else {
				$year_error = "Aasta jäi lisamata!";
			}
			if($year < 1920 or $year > date("Y")){
				$year_error = "Aasta ei sobi!";
			}
			if(isset($_POST["duration_input"]) and !empty($_POST["duration_input"])){
				$duration = $_POST["duration_input"];
			} else {
				$duration_error = "Kestus jäi lisamata!";
			}
			if(isset($_POST["genre_input"]) and !empty($_POST["genre_input"])){
				$genre = $_POST["genre_input"];
			} else {
				$genre_error = "Zanr jäi lisamata!";
			}
			if(isset($_POST["studio_input"]) and !empty($_POST["studio_input"])){
				$studio = $_POST["studio_input"];
			} else {
				$studio_error = "Filmistuudio jäi lisamata!";
			}
			if(isset($_POST["director_input"]) and !empty($_POST["director_input"])){
				$director = $_POST["director_input"];
			} else {
				$director_error = "Rezissöör jäi lisamata!";
			}
			if(empty($title_error) and empty($year_error) and empty($duration_error) and empty($genre_error) and empty($studio_error) and empty($director_error)){
				//loome andmebaasiühenduse
				$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
				//määrame suhtlemisel kasutatava kooditabeli
				$conn->set_charset("utf8");
				$stmt = $conn->prepare("INSERT INTO film (pealkiri, aasta, kestus, zanr, tootja, lavastaja) VALUES(?,?,?,?,?,?)");
				echo $conn->error;
				$stmt->bind_param("siisss", $title, $year, $duration, $genre, $studio, $director);
				$stmt->execute();
				echo $stmt->error;
				//sulgeme käsu/päringu
				$stmt->close();
				$conn->close();
			}
		}
	}
	
	
?>
<!DOCTYPE html>
<html>
<body>
<form method="POST">
	<label for="title_input">Filmi pealkiri</label>
	<input type="text" name="title_input" id="title_input" placeholder="filmi pealkiri" value="<?php echo $title; ?>">
	<span><?php echo $title_error; ?>
	<br>
	<label for="year_input">Valmimisaasta</label>
	<input type="number" name="year_input" id="year_input" min="1912" value="<?php echo $year; ?>">
	<span><?php echo $year_error; ?>
	<br>
	<label for="duration_input">Kestus</label>
	<input type="number" name="duration_input" id="duration_input" min="1" value="<?php echo $duration; ?>" max="600">
	<span><?php echo $duration_error; ?>
	<br>
	<label for="genre_input">Filmi žanr</label>
	<input type="text" name="genre_input" id="genre_input" placeholder="žanr" value="<?php echo $genre; ?>">
	<span><?php echo $genre_error; ?>
	<br>
	<label for="studio_input">Filmi tootja</label>
	<input type="text" name="studio_input" id="studio_input" placeholder="filmi tootja" value="<?php echo $studio; ?>">
	<span><?php echo $studio_error; ?>
	<br>
	<label for="director_input">Filmi režissöör</label>
	<input type="text" name="director_input" id="director_input" placeholder="filmi režissöör" value="<?php echo $director; ?>">
	<span><?php echo $director_error; ?>
	<br>
	<input type="submit" name="film_submit" value="Salvesta">
</form>

<ul>
	<li>Logi <a href="?logout=1">välja</a></li>
	<li>Tagasi <a href="home.php">avalehele</a></li>
</ul>
</body>
</html>