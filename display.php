<?php
session_start();
date_default_timezone_set('Europe/Paris');

include 'functions.php';
include 'connect.php';
// if ($_SESSION['logged_in_user'] == "")
// {
// 	header('Location: index.php');
// 	return ;
// }


?>
<head>
	<title>Camagru</title>
		<link rel='stylesheet' href='css/style.css' type='text/css' media='screen' charset='utf-8'>
		<script src="check.js"></script>
	</head>
	   <?php
	   include 'header.php';
	   $dbh = connect();
	   $req = $dbh->prepare('SELECT id FROM images ORDER BY id DESC LIMIT 0,1');
	   $req->execute();
	   while ($ret = $req->fetch())
	   	$id = $ret['id'];
		$last = $id;

		$req = $dbh->prepare('SELECT id FROM images WHERE id < :id ORDER BY id DESC LIMIT 0,1');
 	   $req->execute(array('id' => $_GET['id']));
 	   while ($ret = $req->fetch())
 	   	$prev = $ret['id'];
		$req = $dbh->prepare('SELECT id FROM images WHERE id > :id ORDER BY id ASC LIMIT 0,1');
 	   $req->execute(array('id' => $_GET['id']));
 	   while ($ret = $req->fetch())
 	   	$next = $ret['id'];

	//    if ($_GET['id'] == "last")
	//    {
	//    	$req = $dbh->prepare('SELECT link, author, date FROM images ORDER BY id DESC LIMIT 0,1');
	//    	$req->execute();
	//    	if ($ret = $req->fetch())
	//    	{
	//    		echo '<img height="500px" src="'.$ret['link'].'"/>';
	//    		$author = $ret['author'];
	//    		echo '<div id="imgauthor">'.$author.'</div>';
	//    		$since = humanTiming(strtotime($ret['date']));
	//    		echo $since." ago\n";
	//    	}
	//    	else {
	//    		echo '<script>location.href="photo.php"</script>';
	//    	}
	//    }
	//    else
	   if ($_GET['id'] > 0 && $_GET['id'] <= $id)
	   {
	   	$req = $dbh->prepare('SELECT link, author, date FROM images WHERE id = :id');
	   	$req->execute(array(
	   		'id' => $_GET['id']
	   	));
	   	$i = 0;
	   	while ($ret = $req->fetch())
	   	{
	   		$i++;
	   		echo '<img height="420px" src="'.$ret['link'].'"/>';
	   		$author = $ret['author'];
	   		echo '<br/><div id="author"><a href="gallery.php?query='.$ret['author'].'">'.$ret['author'].'</a><br/>';
	   		$since = humanTiming(strtotime($ret['date']));
	   		echo $since." ago<br/>";
	   	}
	   	if ($i != 1)
	   	{
	   		echo '<script>alert("Access unauthorized");location.href="photo.php"</script>';
	   	}
	   	$id = $_GET['id'];
	   }
	   else
	   	echo '<script>alert("Access unauthorized");location.href="photo.php"</script>';
		?>

<?php
	   $req = $dbh->prepare('SELECT id FROM members WHERE login = :login');
	   if ($req->execute(array('login' => $_SESSION['logged_in_user'])) && $ret = $req->fetch())
	   {
	   	$uid = $ret['id'];
	   }
	   $req = $dbh->prepare('SELECT COUNT(*) AS "nbr" FROM likes_img WHERE img_id = :img AND member_id = :user');
	   if ($req->execute(array('img' => $id, 'user' => $uid)) && $ret = $req->fetch())
	   {
	   	if ($ret['nbr'] == 1)
	   		$jaime = "je n'aime plus";
	   	else
	   		$jaime = "j'aime";
	   }
	   $req = $dbh->prepare('SELECT COUNT(*) AS "nbr" FROM likes_img WHERE img_id = :id');
	   if ($req->execute(array('id' => $_GET['id'])) && $ret = $req->fetch())
	   {
	   	if ($ret['nbr'] > 0)
	   		$nbrl = $ret['nbr']." <img width='15px' src='images/likelogo.png'>";
		else {
			$nbrl = '<div id="firstlike">Be the first to like this picture!</div>';
		}
	   }
	   if ($_SESSION['logged_in_user'] == $author)
	   		echo '<br/><button id="deletebtn" onclick="checkDel('.$id.')" type="submit" name="id" value="'.$_GET['id'].'">supprimer</button>';
	    if ($_SESSION['logged_in_user'] != "")
	    		echo '<button id="like" onclick="checkLike('.$id.','.$uid.')" type="submit" name="id" value="'.$_GET['id'].'">'.$jaime.'</button>';
		else {
			echo "<br/>";
		}
		echo '<div id="countlikes">'.$nbrl.'</div></div><div id="author">';

		$page_courante = urlencode("montasar.me/display.php?id=".$id);
		// Twitter : Contenu du tweet
		$tweet           = urlencode($_SESSION['logged_in_user'].' a partagé une photo');

		// Pinterest : Contenu du pin et url de l'image
		$pin_texte       = $tweet;
		$pin_url         = "http://montasar.me/camagru/images/201605111006573474.jpg";
		?>
		<!-- Facebook -->
		<br/><br/><div id="partage">share on: <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $page_courante; ?>" target="_blank" class="lien_partage lien_partage_facebook">Facebook</a>

		<!-- Twitter -->
		<a href="https://twitter.com/intent/tweet?text=<?php echo $tweet.' '.$page_courante; ?>" target="_blank" class="lien_partage lien_partage_twitter">Twitter</a>

		<!-- Pinterest -->
		<a href="http://pinterest.com/pin/create/button/?description=<?php echo $pin_texte; ?>&media=<?php echo $pin_image; ?>&url=<?php echo $page_courante; ?>" target="_blank" class="lien_partage lien_partage_pinterest">Pinterest</a>
</div>

	<body>
		<?php
		echo '<div class="comments">';
		$req = $dbh->prepare('SELECT author, date, comment FROM comments WHERE img_id = :id');
		$req->execute(array(
			'id' => $id
		));
		while ($ret = $req->fetch())
		{
			echo '<p><a href="gallery.php?query='.$ret['author'].'">'.$ret['author'];
			echo '</a> ('.humanTiming(strtotime($ret['date'])).' ago): <br/><q>';
			echo $ret['comment'].'</q></p>';
		}
		echo '</div><br/>';
		$url = 'comment.php?id='.$id;
		 ?>
		 <form id="commentform" method="post" action="<?php echo $url;?>">
			 Add a comment <br/><input size=50 name="comment" type="text"></input>
			 <button class="submitbtn" type="submit">submit</button>
		 </form>
	 </div>
	</body>
	<?php
		if ($_GET['id'] != $last)
		echo '<a href="display.php?id='.$next.'">next picture</a>';
		// echo $next;
		echo " - ";
		if ($prev)
		echo '<a href="display.php?id='.$prev.'">prev picture</a>';
		// echo $prev;
	?>
<!-- <footer>
	<a href="index.php">Accueil</a><br/>
	<a href="gallery.php">Galerie</a><br/>
	<a href="inscription.html">S'enregistrer</a><br/>

	<?php if ($_SESSION['logged_in_user'] != ""){
	echo ' 	<a href="edit.php">Modifier mot de passe</a><br/>';
	echo '<a href="remove.php">Supprimer compte '.$_SESSION['logged_in_user'].'</a><br/>';
	echo ' <a href="logout.php">Deconnexion</a><br/>';
}
	?>
</footer> -->
