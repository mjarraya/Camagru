<?php
include 'connect.php';
$dbh = connect();
session_start();
if ($_POST['login'] == "" || $_POST['passwd'] == "" || $_POST['submit'] != "OK")
	echo "<script>alert('Please fill in all fields');location.href='index.php'</script>";
$login = $_POST['login'];
$passwd = hash("whirlpool", $_POST['passwd']);
$req = $dbh->prepare('SELECT * FROM members WHERE login LIKE :login AND password LIKE :passwd');
if ($req->execute(array('login' => $login, 'passwd' => $passwd)) && $ret = $req->fetch())
	$active = $ret['active'];
else
{
	$_SESSION['logged_in_user'] = "";
	echo "<script>alert('Wrong account information');location.href='index.php'</script>";
}

if ($active == 'Y')
{
	$_SESSION['logged_in_user'] = strtolower($_POST['login']);
	//echo "<script>alert('Login successful');location.href='photo.php'</script>";
	header('Location: index.php');
}
else
{
	$_SESSION['logged_in_user'] = "";
	echo "<script>alert('Please activate your account');location.href='index.php'</script>";
}
?>
