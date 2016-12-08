<?php
include 'connect.php';
$dbh = connect();
// $req = $dbh->prepare('SELECT link, id FROM images ORDER BY id DESC');
// $req->execute();
// while ($ret = $req->fetch())
//    echo '<a href="'.'display.php?id='.$ret['id'].'"> <img width="200px" src="'.$ret['link'].'"/>';
session_start();
if (!isset($_GET['query']) || $_GET['query'] == "")
{
	$req = $dbh->prepare('SELECT COUNT(*) AS total FROM images');
	$req->execute();
}
else{
	$req = $dbh->prepare('SELECT COUNT(*) AS total FROM images WHERE author LIKE :query');
	$req->execute(array('query' => $_GET['query']));
}
if ($_GET['likes'] == "true")
{
	$req = $dbh->prepare('SELECT id FROM members WHERE login LIKE :user');
	if ($req->execute(array('user' => $_SESSION['logged_in_user'])) && $ret = $req->fetch())
	{
	$id = $ret['id'];
	$req = $dbh->prepare('SELECT COUNT(*) AS total FROM likes_img WHERE member_id = :id');
	if ($req->execute(array('id' => $id)) && $ret = $req->fetch())
		$total = $ret['total'];
	}
}
while ($ret = $req->fetch())
	$total = $ret['total'];
$ipg = 9;
$npg = ceil($total/$ipg);

if (isset($_GET['page']))
{
	$pg = intval($_GET['page']);
	if ($pg > $npg)
	{
		$pg = $npg;
	}
}
else{
	$pg = 1;
}
$pe = ($pg -1) * $ipg;

 ?>
 <head>
 	<title>Camagru</title>
 		<link rel='stylesheet' href='css/style.css' type='text/css' media='screen' charset='utf-8'>
 	</head>

	<?php
	$actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	//if ($actual_link != "http://montasar.me/camagru/index.php" && $_SESSION['logged_in_user'] != "")
		include 'header.php';
	if ($total == 0 && isset($_GET['query']))
		echo "<h3>No image found for \"".$_GET['query']."\"</h3> <a href='index.php'>return home</a>";
	// if ($_SESSION['logged_in_user'] == "")
	// 	echo "<a href='index.php'>Accueil</a><br/>";
	if (!isset($_GET['query']) || $_GET['query'] == "")
	{
		$req = $dbh->prepare('SELECT author, link, id FROM images ORDER BY id DESC LIMIT '.$pe.', '.$ipg.'');
		$req->execute();
	}
	else {
		$req = $dbh->prepare('SELECT author, link, id FROM images WHERE author LIKE :query ORDER BY id DESC LIMIT '.$pe.', '.$ipg.'');
		$req->execute(array('query' => htmlspecialchars($_GET['query'])));
	}
	if ($_GET['likes'] == "true")
	{
		$req1 = $dbh->prepare('SELECT img_id AS id FROM likes_img WHERE member_id = :member_id  ORDER BY id DESC LIMIT '.$pe.', '.$ipg.'');
		$req1->execute(array('member_id'=> $id));
		while ($ret1 = $req1->fetch())
		{
			$req = $dbh->prepare('SELECT author, link, id FROM images WHERE id = :id');
			$req->execute(array(
				'id' => $ret1['id']
			));
			while ($ret = $req->fetch())
			echo '<div class="galleryphoto"><a href="'.'display.php?id='.$ret1['id'].'"> <img  width="500px" src="'.$ret['link'].'"/></a><br/>
<a href="gallery.php?query='.$ret['author'].'">'.$ret['author'].'</a></div><br/><br/>';
				// echo '<a href="'.'display.php?id='.$ret1['id'].'"> <img width="200px" src="'.$ret['link'].'"/></a>';

		}
	}
	while ($ret = $req->fetch())
	// echo '<a href="'.'display.php?id='.$ret['id'].'"> <img width="500px" src="'.$ret['link'].'"/></a><br/><br/>';
	echo '<div class="galleryphoto"><a href="'.'display.php?id='.$ret['id'].'"> <img  width="500px" src="'.$ret['link'].'"/></a><br/>
<a href="gallery.php?query='.$ret['author'].'">'.$ret['author'].'</a></div><br/><br/>';
		// echo '<a href="'.'display.php?id='.$ret['id'].'"> <img width="200px" src="'.$ret['link'].'"/></a>';
	echo "<br/>";
	for ($i = 1; $i <= $npg; $i++)
	{
		if ($i == $pg)
		{
			echo ' '.$i.' ';
		}
		else{
			if ($_GET['likes'] == "true")
			echo ' <a class="page" href="gallery.php?likes=true&page='.$i.'">'.$i.'</a>';
			else
			echo ' <a class="page" href="gallery.php?page='.$i.'">'.$i.'</a>';
		}
	}

	 ?>
 <!-- <footer>
 	<a href="index.php">Accueil</a><br/>
 	<a href="inscription.html">S'enregistrer</a><br/>
 	<?php if ($_SESSION['logged_in_user'] != ""){
	echo ' 	<a href="edit.php">Modifier mot de passe</a><br/>';
	echo '<a href="remove.php">Supprimer compte '.$_SESSION['logged_in_user'].'</a><br/>';
	echo ' <a href="logout.php">Deconnexion</a><br/>';
}
	?>
 </footer> -->
<!-- <?php
// if ($actual_link != "http://montasar.me/camagru/index.php")
// 		include('footer.php');
?> -->
