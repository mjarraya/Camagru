<?php
include 'connect.php';
$dbh = connect();
$login = $_GET['login'];
$cle = $_GET['cle'];
$req = $dbh->prepare("SELECT cle, active FROM members WHERE login LIKE :login");
if ($req->execute(array('login' => $login)) && $ret = $req->fetch())
{
	$cledb = $ret['cle'];
	$active = $ret['active'];
}
if ($active == 'Y')
	echo "<script>alert('Your account is already active');location.href='index.php'</script>";
else
{
	if ($cle == $cledb)
	{
		echo "<script>alert('Your account is now active');location.href='index.php'</script>";
		$req = $dbh->prepare("UPDATE members SET active = 'Y' WHERE login LIKE :login");
		$req->execute(array(
			'login' => $login
		));
	}
	else
		echo "<script>alert('Oops! Validation failed');location.href='index.php'</script>";
}
