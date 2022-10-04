<?php
	require_once "../../config.php";
	
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
	echo $stmt->error;
	$stmt->close();
	$conn->close();
?>
<!DOCTYPE html>
<html>
<body>
<h3><?php echo $title_from_db ?></h3>
<ul>
		<li>Valmistamisaasta: <?php echo $year_from_db ?></li>
		<li>Kestus: <?php echo $duration_from_db ?></li>
		<li>Źanr: <?php echo $genre_from_db ?></li>
		<li>Tootja: <?php echo $studio_from_db ?></li>
		<li>Lavastaja: <?php echo $director_from_db ?></li>
</ul>

</body>
</html>