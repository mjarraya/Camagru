<?php
date_default_timezone_set('Europe/Paris');

session_start();
header('Content-type: image/png');

include 'functions.php';
include 'connect.php';
$dbh = connect();

$imageData = file_get_contents('php://input');
$file = "images/filters/".$_GET['filter'].".png";
$src = imagecreatefrompng($file);

$filteredData=substr($imageData, strpos($imageData, ",")+1);
$unencodedData=base64_decode($filteredData);


$dest = imagecreatefromstring($unencodedData);
$wid = imagesx($dest);
$hei = imagesy($dest);
if ($wid == 0 || $hei == 0)
{
	echo "error";
	return ;
}
ob_start();
if ($wid != 560){
	$srccpy = $dest;
	$dest = imagecreatetruecolor(560, 420);
	imagecopyresized($dest, $srccpy, 0, 0, 0, 0, 560, 420, $wid, $hei);
}
if ($_GET['filter'] == 'jeanneige')
	imagecopy($dest, $src, 152, 78, 0, 0, 560, 420);
else if ($_GET['filter'] == 'trump')
	imagecopy($dest, $src, 0, -100, 0, 0, 560, 420);
else
	imagecopy($dest, $src, 0, -100, 0, 0, 560, 420);
imagejpeg($dest);

$contents = ob_get_contents();
ob_end_clean();


$microtime = substr(microtime(true), - 4);
$time = date('Y-m-d G:i:s', time());
$cleantime = date('YmdGis', time());
$path = "images/".$cleantime.$microtime.".png";
// $tpath = "images/t".$cleantime.$microtime.".jpg";
$fp = fopen( $path, 'wb' );
fwrite( $fp, $contents);
fclose( $fp );
echo $path;
//make_thumb($path, $tpath, 100, 0);
//$tpath = $path;
$req = $dbh->prepare('INSERT INTO images(link, author, date) VALUES(:link, :author, :date)');
$req->execute(array(
	'link' => $path,
//	'tlink' => $tpath,
	'author' => $_SESSION['logged_in_user'],
	'date' => $time
));

?>
