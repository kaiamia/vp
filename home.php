<?php 
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
	
	require_once "header.php"; 
?>
<p>Sisse logitud: <?php echo $_SESSION["firstname"] ." " .$_SESSION["lastname"]; ?> </p>
<ul>
	<li>Logi <a href="?logout=1">välja</a></li>
	<li><a href="leht1.php">Filmide lisamise lehele</a></li>
	<li><a href="leht2.php">Filmide loetelu</a></li>
	<li><a href="gallery_photo_upload.php">Fotode galeriisse laadimine</a></li>
</ul>
<?php require_once "footer.php"; ?>