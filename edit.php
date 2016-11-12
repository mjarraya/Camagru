<?php
session_start();
 ?>

<html>
<head>
	<link rel='stylesheet' href='css/style.css' type='text/css' media='screen' charset='utf-8'>
	<title>Change password</title>
</head>
	<body>
			<?php include 'header.php'; ?>
		<form id="loginform-mid" method="post" action="modif.php">
			<input class="idinput" placeholder="login" value="<?php echo $_SESSION['logged_in_user'];?>" type="text" name="login"/><br/>
			<input class="idinput" placeholder="current password" type="password" name="oldpw"/><br/>
			<input class="idinput" placeholder="new password" type="password" name="newpw"/><br/>
			<input class="submitbtn" type="submit" name="submit" value="OK"/>
		</form>
		<!-- <footer>
			<a href="index.php">Accueil</a><br/>
		 	<a href="gallery.php">Galerie</a><br/>
			<?php if ($_SESSION['logged_in_user'] != "")
				echo '<a href="logout.php">Deconnexion ['.$_SESSION['logged_in_user'].']</a>';?>
		</footer> -->
	</body>
</html>
