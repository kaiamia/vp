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
	require_once "fnc_gallery.php";
?>
<p>Sisse logitud: <?php echo $_SESSION["firstname"] ." " .$_SESSION["lastname"]; ?> </p>
<ul>
	<li>Logi <a href="?logout=1">välja</a></li>
	<li><a href="leht1.php">Filmide lisamise lehele</a></li>
	<li><a href="leht2.php">Filmide loetelu</a></li>
	<li><a href="gallery_photo_upload.php">Fotode galeriisse laadimine</a></li>
	<li><a href="gallery_public.php">Avalike fotode galerii</a></li>
	<li><a href="gallery_own.php">Minu fotod</a></li>
</ul>
<h2>Avalik foto</h2>
<div class="gallery">
	<?php echo read_public_photo(3, 1); ?>
</div>
<?php require_once "footer.php"; ?>