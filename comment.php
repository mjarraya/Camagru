<?php
date_default_timezone_set('Europe/Paris');
include 'functions.php';
session_start();
$array = explode("/", $_SERVER['REQUEST_URI']);
$path = $array[1];
// echo $path;
$url = 'display.php?id='.$_GET['id'];
if ($_SESSION['logged_in_user']== "")
{
	echo '<script>alert("You must be logged in to post a comment");location.href="'.$url.'";</script>';
	return ;
}
if ($_POST['comment'] != ""){
include 'connect.php';
$dbh = connect();
$req = $dbh->prepare('INSERT INTO comments(img_id, author, date, comment) VALUES(:img_id, :author, :date, :comment)');
$req->execute(array(
	'img_id' => $_GET['id'],
	'author' => $_SESSION['logged_in_user'],
	'date' => date('Y-m-d G:i:s', time()),
	'comment' => htmlspecialchars($_POST['comment'])
));
$req = $dbh->prepare('SELECT author FROM images WHERE id = :id');
if ($req->execute(array('id' => $_GET['id'])) && $ret = $req->fetch())
{
	$author = $ret['author'];
}
$req = $dbh->prepare('SELECT mail FROM members WHERE login LIKE :user');
if ($req->execute(array('user' => $author)) && $ret = $req->fetch())
{
	$recipient = $ret['mail'];
	$subject = "Camagru Notification";
	$header = "From camagru@42.fr";
	$message = "Hello ".$author."!

	You have a new comment on your picture!
	Click on the link below to check it."."\nhttp://localhost:8080/".$path."/".$url."\n";
	mail($recipient, $subject, $message, $header);
}
header('Location: '.$url);
}
else {
	echo '<script>alert("Comment cannot be blank");location.href="'.$url.'";</script>';
}

 ?>
