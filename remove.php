<?php
session_start();
if ($_SESSION['logged_in_user'] == "")
{
	header('Location: index.php');
	return ;
}
?>
<html>
<head>
	<link rel='stylesheet' href='css/style.css' type='text/css' media='screen' charset='utf-8'>
	<title>Remove account</title>
</head>
	<body>
		<?php include 'header.php'; ?>
		<form id="loginform-mid" method="post" action="delete.php">
			<input class="idinput" placeholder="login" value="<?php echo $_SESSION['logged_in_user'];?>" type="text" name="login"/><br/>
			<input class="idinput" placeholder="password" type="password" name="passwd"/>
			<input type="checkbox" name="delete" value="yes">
			<input class="submitbtn" type="submit" name="submit" value="OK"/><br/>
			<small style="font-size:10px;position:relative;bottom:10px;">Are you sure ?</small>
		</form>
		<!-- <footer><a href="index.php">Accueil</a><br/>
	 	<a href="gallery.php">Galerie</a><br/>
	 	<a href="edit.php">Modifier mot de passe</a><br/>
	 	<a href="logout.php">Deconnexion</a><br/>
</footer> -->
	</body>
</html>
