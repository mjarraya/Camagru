<?php
session_start(); ?>
<link rel='stylesheet' href='css/style.css' type='text/css' media='screen' charset='utf-8'>
<link rel="icon" type="image/png" href="images/logo.png">

<header>
	<div id="navmenu">
	<a id="logo" title="home" href="index.php"><img  src="images/logo.png"></a>
	<form id="transparent" method="get"  action="gallery.php"><input name="query" id="search" size="40" placeholder="search for user"/></form>
		<ul>
	<li><a id="userlogo" href="#"><img src="images/userlogo.png"></a>
		<ul>
			<?php
			if ($_SESSION['logged_in_user'] != "")
			echo '
			<li><a href="edit.php">change password</a></li>
			<li><a href="remove.php">delete account</a></li>
			<li><a href="logout.php">logout ('.$_SESSION['logged_in_user'].')</a></li>';
			else {
				echo '
				<li><a href="index.php">login</a></li>
				<li><a href="inscription.html">register</a></li>
				<li><a href="lost.html">recover password</a></li>
				';
			}
			?>
		</ul>
	</li>
	<li><a id="likelogo" title="liked photos" href="#"><img src="images/likelogo.png"></a>
		<ul>
			<?php
			if ($_SESSION['logged_in_user'] != "")
			echo '
			<li><a href="gallery.php?query='.$_SESSION['logged_in_user'].'">my photos</a></li>
			<li><a href="gallery.php?likes=true">photos I liked</a></li>
			';
			?>
			<li><a href="liked.php">most liked pictures</a></li>
		</ul>
	</li>
	<?php
	$actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	if ($actual_link != "http://montasar.me/camagru/gallery.php")
	{
		echo '<li><a id="gallerylogo" title="gallery" href="gallery.php"><img src="images/gallerylogo.png"></a></li>';
	}
	 ?>
 </ul>
 </div>
	<hr width="100%;"/>
</header>
