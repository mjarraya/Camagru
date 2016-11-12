<?php
include 'functions.php';
include 'connect.php';
$dbh = connect();
session_start();
$login = $_GET['login'];
if ($login != "")
	$_SESSION['login'] = $login;
$token = $_GET['token'];
if ($token != "")
	$_SESSION['token'] = $token;

$passwd = hash("whirlpool", $_POST['newpw']);
$req = $dbh->prepare("SELECT token FROM members WHERE login LIKE :login");
if ($req->execute(array('login' => $_SESSION['login'])) && $ret = $req->fetch())
{
	$tokendb = $ret['token'];
}
if ($_POST['submit'] == "OK")
{
if ($_SESSION['token'] == $tokendb)
{
	if (strlen($_POST['newpw']) > 12)
	{
		echo "<script>alert('Password is too long (max 12 characters)');location.href='reset.php';</script>";
		return ;
	}
	if (strlen($_POST['newpw']) < 6)
	{
		echo "<script>alert('Password is too short (between 6 and 12 characters)');location.href='reset.php';</script>";
		return ;
	}
	if (!accepted_cred($_POST['newpw']))
	{
		echo "<script>alert('Password must contain only alphanumeric characters and underscores/hyphens');location.href='reset.php';</script>";
		return ;
	}
	$req = $dbh->prepare("UPDATE members SET password = :password, token = :token WHERE login LIKE :login");
	$req->execute(array(
		'password' => $passwd,
		'token' => str_shuffle($tokendb),
		'login' => $_SESSION['login']
	));
	echo "<script>alert('Password has been changed, login to use new password');location.href='logout.php';</script>";

}
else
	echo "<script>alert('The reset link has already been used, please request a new link');location.href='lost.html';</script>";

}
?>
<html>
<head>
	<link rel='stylesheet' href='css/style.css' type='text/css' media='screen' charset='utf-8'>
	<title>Reset password</title>
</head>
	<body>
		<?php include ('header.php'); ?>
		<form method="post" action="reset.php">
			<input class="idinput" width="50px" placeholder="newpassword" type="password" name="newpw"/><br/>
			<input type="submit" name="submit" value="OK"/>
		</form>
		<!-- <footer></footer> -->
	</body>
</html>
