<?php
include 'functions.php';
include 'connect.php';
$dbh = connect();
session_start();
if ($_SESSION['logged_in_user'] == "")
{
	header('Location: index.php');
	return ;
}
if (!$_POST['oldpw'] || !$_POST['login'] || !$_POST['submit'] || !$_POST['newpw'] || $_POST['oldpw'] === "" || $_POST['newpw'] === "" || $_POST['login'] === "" || $_POST['submit'] !== "OK")
{
	echo "<script>alert('Please fill in all fields');location.href='edit.php';</script>";
	return;
}
if (strlen($_POST['newpw']) > 12)
{
	echo "<script>alert('Password is too long (max 12 characters)');location.href='edit.php';</script>";
	return ;
}
if (strlen($_POST['newpw']) < 6)
{
	echo "<script>alert('Password is too short (between 6 and 12 characters)');location.href='edit.php';</script>";
	return ;
}
if (!accepted_cred($_POST['newpw']))
{
	echo "<script>alert('Password must contain only alphanumeric characters and underscores/hyphens');location.href='edit.php';</script>";
	return ;
}
$login = $_POST['login'];
$passwd = hash("whirlpool", $_POST['oldpw']);
$req = $dbh->prepare('SELECT * FROM members WHERE login LIKE :login AND password LIKE :passwd');
if ($req->execute(array('login' => $login, 'passwd' => $passwd)) && $ret = $req->fetch())
{
$req = $dbh->prepare('UPDATE members SET password = :newpw WHERE login = :login AND password = :oldpw');
$req->execute(array(
	'newpw' => hash("whirlpool", $_POST['newpw']),
	'login' => $_POST['login'],
	'oldpw' => hash("whirlpool", $_POST['oldpw'])
));
echo "<script>alert('Password successfully modified');location.href='index.php';</script>";
}
else
	echo "<script>alert('Please check credentials');location.href='edit.php';</script>";
?>
