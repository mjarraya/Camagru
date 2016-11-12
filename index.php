<?php
session_start();
if ($_SESSION['logged_in_user'] != "")
{
	header('Location: photo.php');
	return ;
}
?>
<html>
<head>
	<link rel='stylesheet' href='css/style.css' type='text/css' media='screen' charset='utf-8'>
	<title>camagru</title>
</head>
<body>
		<?php
		//include 'header.php';
		?>
		<div id="background"><img src="images/background.png"></div>
		<form id="loginform" method="post" action="login.php"><br/>
			<h1>camagru</h1>
			<input class="idinput" placeholder="login" type="text" name="login"/><br/>
			<input class="idinput" placeholder="password" type="password" name="passwd"/><br/>
			<input class="submitbtn" type="submit" name="submit" value="OK"/><br /><br/>
			<a href="inscription.html">register</a><br/>
			<a href="lost.html">forgotten password?</a><br/>
			<a href="gallery.php">view all photos</a>
		</form>
</body>
<footer>© 2016 mjarraya</footer>
<?php
//include 'gallery.php';
//include 'footer.php';
?>
</html>
