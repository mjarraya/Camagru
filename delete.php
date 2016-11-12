<?php
include 'connect.php';
session_start();
if ($_SESSION['logged_in_user'] == "")
{
	header('Location: index.php');
	return ;
}
$dbh = connect();
if ($_POST['login'] == "" || $_POST['passwd'] == "" || $_POST['submit'] != "OK")
{
	echo "<script>alert('Please fill in all fields');location.href='remove.php';</script>";
	return;
}
$login = $_POST['login'];
$passwd = hash("whirlpool", $_POST['passwd']);
$req = $dbh->prepare('SELECT * FROM members WHERE login LIKE :login AND password LIKE :passwd');
if ($req->execute(array('login' => $login, 'passwd' => $passwd)) && $ret = $req->fetch())
{
	$id = $ret['id'];
	$author = $ret['login'];
}
else
	echo "<script>alert('Please ensure you entered correct credentials');location.href='remove.php';</script>";

if ($_POST['delete'] == "yes")
{
$req = $dbh->prepare('DELETE FROM members WHERE members.id = :id');
$req->execute(array(
	'id' => $id
));
$req = $dbh->prepare('DELETE FROM images WHERE author = :author ');
$req->execute(array(
	'author' => $author
));
$req = $dbh->prepare('DELETE FROM likes_img WHERE member_id = :id');
$req->execute(array(
	'id' => $id
));
echo "<script>alert('Account successfully removed');location.href='logout.php';</script>";
}
else
	echo "<script>alert('No changes made to account, redirecting you');location.href='photo.php';</script>";

?>
<!--
<html>
<head>
	<link rel='stylesheet' href='css/style.css' type='text/css' media='screen' charset='utf-8'>
	<title>Supprimer le compte</title>
</head>
	<body>
		<header><h1>Camagru</h1></header><br/>
		<form method="post" action="delete.php">
			Identifiant<input type="text" name="login"/><br/>
			Mot de passe<input type="password" name="passwd"/><br/>
			Supprimer mon compte<input type="checkbox" name="delete" value="yes"><br/>
			<input type="submit" name="submit" value="OK"/>
		</form>
		<footer><a href="index.php">Accueil</a><br/>
	 	<a href="gallery.php">Galerie</a><br/>
	 	<a href="edit.php">Modifier mot de passe</a><br/>
	 	<a href="logout.php">Deconnexion</a><br/>
</footer>
	</body>
</html> -->
