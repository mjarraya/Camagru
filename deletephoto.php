<?php

session_start();
include 'connect.php';

$dbh = connect();
$req = $dbh->prepare('SELECT author FROM images WHERE id = :id');
$req->execute(array(
	'id' => $_GET['id']
));
while ($ret = $req->fetch())
	$author = $ret['author'];
if ($author == $_SESSION['logged_in_user'])
{
	$req = $dbh->prepare('DELETE FROM images WHERE id = :id');
	$req->execute(array(
		'id' => $_GET['id']
	));
	//echo '<script>alert("Removal successful");location.href="photo.php"</script>';
	header('Location: photo.php');
}
else
{
	header('Location: index.php');
	return ;
}

 ?>
