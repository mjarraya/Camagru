<?php
include('connect.php');
$dbh = connect();

include ('header.php');
$req = $dbh->prepare('SELECT img_id, COUNT(img_id) AS cnt FROM likes_img GROUP BY img_id ');
$req->execute();
$total = $req->rowCount();
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
$req = $dbh->prepare('SELECT img_id, COUNT(img_id) AS cnt FROM likes_img GROUP BY img_id ORDER BY cnt DESC LIMIT '.$pe.', '.$ipg.'');
$req->execute();
while ($ret = $req->fetch())
{
	$req1 = $dbh->prepare('SELECT author, link FROM images WHERE id = :id ORDER BY id ');
	if ($req1->execute((array('id' => $ret['img_id']))) && $ret1 = $req1->fetch())
		echo '<div class="galleryphoto"><a href="'.'display.php?id='.$ret['img_id'].'"> <img width="500px" src="'.$ret1['link'].'"/></a><br/><a href="gallery.php?query='.$ret1['author'].'">'.$ret1['author'].'</a></div><br/><br/>';
}
echo "<br/>";
for ($i = 1; $i <= $npg; $i++)
{
	if ($i == $pg)
	{
		echo ' '.$i.' ';
	}
	else{
		echo ' <a class="page" href="liked.php?page='.$i.'">'.$i.'</a>';
	}
}

 ?>
