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
	
	//loome andmebaasiühenduse
	$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
	//määrame suhtlemisel kasutatava kooditabeli
	$conn->set_charset("utf8");
	//valmistame ette SQL keeles päringu
	$stmt = $conn->prepare("SELECT pealkiri, aasta, kestus, zanr, tootja, lavastaja FROM film");
	echo $conn->error;
	//seome loetavad andmed muutujatega
	$stmt->bind_result($title_from_db, $year_from_db, $duration_from_db, $genre_from_db, $studio_from_db, $director_from_db);
	$stmt->execute();
	$movie_html = null;
	while($stmt->fetch()){
		$movie_html .= "<h3>" .$title_from_db ."</h3> \n";
		$movie_html .= "<ul>\n";
		$movie_html .= "<li>Valmistamisaasta: "  .$year_from_db ."</li>\n";
		$movie_html .= "<li>Kestus: "  .$duration_from_db ."</li>\n";
		$movie_html .= "<li>Žanr: "  .$genre_from_db ."</li>\n";
		$movie_html .= "<li>Tootja: "  .$studio_from_db ."</li>\n";
		$movie_html .= "<li>Lavastaja: "  .$director_from_db ."</li>\n";
		$movie_html .= "</ul>\n";
	}
	echo $stmt->error;
	$stmt->close();
	$conn->close();
	
	
?>
<!DOCTYPE html>
<html>
<body>
<ul>
	<li>Logi <a href="?logout=1">välja</a></li>
	<li>Tagasi <a href="home.php">avalehele</a></li>
</ul>
<hr>
<h2>Eesti filmid</h2>
<?php echo $movie_html; ?>





</body>
</html>