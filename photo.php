<?php
include 'functions.php';
include 'connect.php';
session_start();
$dbh = connect();
if ($_SESSION['logged_in_user'] == "")
{
	header('Location: index.php');
	return ;
}

/*if (!empty($_FILES)){
$img = $_FILES['img'];
$ext = strtolower(substr($img['name'], -3));
if ($ext == "png" || $ext == "jpg")
{
move_uploaded_file($img['tmp_name'], "images/".$_SESSION['logged_in_user'].$img['name']);
}
else
echo "<script>alert('Format d\'image non valide: jpg et png uniquement');location.href='photo.php';</script>";
}*/
?>
<html>
<head>
	<title>Camagru</title>
	<link rel='stylesheet' href='css/style.css' type='text/css' media='screen' charset='utf-8'>
	<link rel="icon" type="image/png" href="images/logo.png">
	<script src="photo.js"></script>
</head>
<body>
	<?php include 'header.php'; ?>

		<div id="gallery">
					<a href="gallery.php">Galerie</a><br/><br/>
					<?php
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
					?>
				</div>
				<div class="emptydiv">
				</div>
	<div class="contentarea">
		<div class="camera">

							<div class="filterchoice">Mustache
								<input type="radio" onclick="filterPreview(this.id)" id="moustache" name="filter" value="moustache" /><img width="50px" src="images/filters/moustache.png">Trump
								<input type="radio" onclick="filterPreview(this.id)" id="trump" name="filter" value="trump" /><img width="50px" src="images/filters/trump.png">Jean Neige
								<input type="radio" onclick="filterPreview(this.id)" id="jeanneige" name="filter" value="jeanneige" /><img width="50px" src="images/filters/jeanneige.png">
							</div><br/>
			<img id="apmoustache" src="images/filters/moustache.png">
			<img id="aptrump" src="images/filters/trump.png">
			<img id="apjeanneige" src="images/filters/jeanneige.png">
			<video id="video"></video>
			<button onclick="myRefresh()" id="startbutton">Take a pic</button>

			<form class="upform" enctype="multipart/form-data">or upload a picture
				<input type="file" name="file" id="imgupload" onchange="previewFile()"/>
				<input class="upbtn" type="button" id="imgsubmit" onclick="editUpload()" value="Take a pic"/>
				<!-- <input onclick="myUpload" type="submit" name="submit" /> -->
			</form><br/>
			</div><br/>
			<canvas id="canvas"></canvas>
			<div class="output">
				<img id="photo"></div>

			</div>


			<script>
			function filterPreview(id)
			{
				if (id == "moustache")
				{
					var obj = document.querySelector('#apmoustache');
					obj.style.display = "block";
					document.querySelector('#aptrump').style.display = "none";
					document.querySelector('#apjeanneige').style.display = "none";
					document.querySelector('#startbutton').style.backgroundColor = "rgb(56,151,240)";
					document.querySelector('#startbutton').style.borderColor = "rgb(56,151,240)";
				}
				if (id == "trump")
				{
					var obj = document.querySelector('#aptrump');
					obj.style.display = "block";
					document.querySelector('#apmoustache').style.display = "none";
					document.querySelector('#apjeanneige').style.display = "none";
					document.querySelector('#startbutton').style.backgroundColor = "rgb(56,151,240)";
					document.querySelector('#startbutton').style.borderColor = "rgb(56,151,240)";
				}
				if (id == "jeanneige")
				{
					var obj = document.querySelector('#apjeanneige');
					obj.style.display = "block";
					document.querySelector('#aptrump').style.display = "none";
					document.querySelector('#apmoustache').style.display = "none";
					document.querySelector('#startbutton').style.backgroundColor = "rgb(56,151,240)";
					document.querySelector('#startbutton').style.borderColor = "rgb(56,151,240)";
				}
			}
			</script>
		</body>

		<!-- <footer>
		<a href="edit.php">Modifier mot de passe</a><br/>
		<?php echo '<a href="remove.php">Supprimer compte '.$_SESSION['logged_in_user'].'</a><br/>';?>
		<a href="logout.php">Deconnexion</a><br/>
	</footer> -->
<footer>© 2016 mjarraya</footer>
	</html>
