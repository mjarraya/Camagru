<?php

include 'connect.php';
$dbh = connect();
$array = explode("/", $_SERVER['REQUEST_URI']);
$path = $array[1];

if ($_POST['login'] == "" || $_POST['mail'] == "" || $_POST['submit'] != "OK")
{
	echo "<script>alert('Please fill in all fields');location.href='lost.html';</script>";
	return;
}
$token = md5(microtime(TRUE)*100000);
$req = $dbh->prepare("UPDATE members SET token = :token WHERE login like :login");
$req->execute(array(
	'token' => $token,
	'login' => $_POST['login']
));
$login = $_POST['login'];
$mail = $_POST['mail'];
$req = $dbh->prepare('SELECT * FROM members WHERE login LIKE :login AND mail LIKE :mail');
if ($req->execute(array('login' => $login, 'mail' => $mail)) && $ret = $req->fetch())
{
	$recipient = $_POST['mail'];
	$subject = "Reset your password";
	$header = "From camagru@42.fr";
	$link = 'http://localhost:8080/'.$path.'/reset.php?login='.urlencode($_POST['login']).'&token='.urlencode($token);
	$message = 'Welcome to Camagru!

	To reset your password, please click on the link below.'."\n".$link.'
	-------------
	Do not reply.';
	mail($recipient, $subject, $message, $header);
	echo "<script>alert('Click the link in email to reset your password');location.href='index.php';</script>";
}
else
	echo "<script>alert('Please ensure you entered correct credentials');location.href='lost.html';</script>";
 ?>
