<?php 
	//session_start();
	require_once "Classes/SessionManager.class.php";
	require_once "fnc_gallery.php";
	SessionManager::sessionStart("vp", 0, "~kaldkaia/vp/", "greeny.cs.tlu.ee");
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
	
	//tegelen küpsistega
	$last_visitor = "pole teada";
	
	if(isset($_COOKIE["lastvisitor"]) and !empty($_COOKIE["lastvisitor"])){
		$last_visitor = $_COOKIE["lastvisitor"];
	}
	
	//salvestan küpsise
	//nimi, väärtus, aegumistähtaeg, veebikataloog, domeen, https kasutamine
	//https      isset($_SERVER["HTTPS"])
	setcookie("lastvisitor", $_SESSION["firstname"] ." " .$_SESSION["lastname"], time() + (60 * 60 * 24 * 8), "~kaldkaia/vp/", "greeny.cs.tlu.ee", true, true);
	//küpsise kustutamine: expire ehk aegumistähtaeg pannakse minevikus: time() - 3000
	require_once "header.php"; 
	require_once "fnc_gallery.php";
	if($last_visitor != $_SESSION["firstname"] . " " .$_SESSION["lastname"]){
		echo "<p>Viimati oli sisseloginud: " .$last_visitor ."</p> \n";
	}
?>
<p>Sisse logitud: <?php echo $_SESSION["firstname"] ." " .$_SESSION["lastname"]; ?> </p>
<h2>Profiilipilt</h2>
<div class="gallery">
<?php echo show_profile_picture(); ?>
</div>
<hr>
<ul>
	<li>Logi <a href="?logout=1">välja</a></li>
	<li><a href="leht1.php">Filmide lisamise lehele</a></li>
	<li><a href="leht2.php">Filmide loetelu</a></li>
	<li><a href="gallery_photo_upload.php">Fotode galeriisse laadimine</a></li>
	<li><a href="gallery_public.php">Avalike fotode galerii</a></li>
	<li><a href="gallery_own.php">Minu fotod</a></li>
	<li><a href="user_profile.php">Minu profiil</a></li>
</ul>
<!--<h2>Avalik foto</h2>
<div class="gallery">
	<?php echo read_public_photo(3, 1); ?>
</div> -->
<?php require_once "footer.php"; ?>