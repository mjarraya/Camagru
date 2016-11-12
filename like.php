<?php
include 'connect.php';
$dbh = connect();
$req = $dbh->prepare('SELECT img_id, member_id FROM likes_img WHERE img_id = :img AND member_id = :user');
if ($req->execute(array(
	'img' => $_GET['id'],
	'user' => $_GET['user']
))){
	if ($ret = $req->fetch())
{
	$req = $dbh->prepare('DELETE FROM likes_img WHERE img_id = :img AND member_id = :user');
	$req->execute(array(
		'img' => $_GET['id'],
		'user' => $_GET['user']
	));
}
else{
	$req = $dbh->prepare('INSERT INTO likes_img(img_id, member_id) VALUES(:img, :user)');
	$req->execute(array(
		'img' => $_GET['id'],
		'user' => $_GET['user']
	));
}
}
$req = $dbh->prepare('SELECT COUNT(*) AS "nbr" FROM likes_img WHERE img_id = :id');
if ($req->execute(array('id' => $_GET['id'])) && $ret = $req->fetch())
{
	if ($ret['nbr'] > 0)
		echo $ret['nbr']." <img width='15px' src='images/likelogo.png'>";
	else
		echo '<div id="firstlike">Be the first to like this picture!</div>';
}
 ?>
