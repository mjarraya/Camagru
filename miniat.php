<?php
session_start();
echo '
<a href="gallery.php">Galerie</a><br/><br/>';
include 'connect.php';
$dbh = connect();
$req = $dbh->prepare('SELECT link, id FROM images WHERE author = :author ORDER BY id DESC LIMIT 0,9');
$req->execute(array(
   'author' => $_SESSION['logged_in_user']
));
$i = 1;
while ($ret = $req->fetch())
 {
	 echo '<a href="'.'display.php?id='.$ret['id'].'"> <img height="100px" src="'.$ret['link'].'"/></a>';
	 if ($i % 3 == 0)
		echo "<br/>";
		$i++;
	}
//echo "<script>location.reload();</script>";
?>
