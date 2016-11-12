<?php
session_start();
if ($_SESSION['logged_in_user'] != "")
	$_SESSION['logged_in_user'] = "";

include 'database.php';



try {
	$dbh = new PDO ($DB_DSN, $DB_USER, $DB_PASSWORD);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->exec('CREATE TABLE IF NOT EXISTS members(id int NOT NULL AUTO_INCREMENT,
		mail VARCHAR(320) UNIQUE NOT NULL,
		login VARCHAR(12) UNIQUE NOT NULL,
		password VARCHAR(128) NOT NULL,
		active ENUM(\'Y\', \'N\') NOT NULL DEFAULT \'N\',
		cle VARCHAR(32) NOT NULL,
		token VARCHAR(32) NOT NULL,
		PRIMARY KEY(id)
	)');
	$dbh->exec('CREATE TABLE IF NOT EXISTS images(
		id int NOT NULL AUTO_INCREMENT,
		link VARCHAR(32) UNIQUE NOT NULL,
		-- tlink VARCHAR(32) UNIQUE NOT NULL,
		author VARCHAR(12) NOT NULL,
		date VARCHAR(32) NOT NULL,
		PRIMARY KEY(id)
	)');
	$dbh->exec('CREATE TABLE IF NOT EXISTS comments(
		id int NOT NULL AUTO_INCREMENT,
		img_id int NOT NULL,
		author VARCHAR(12) NOT NULL,
		date VARCHAR(32) NOT NULL,
		comment TEXT,
		PRIMARY KEY(id)
	)');
	$dbh->exec('CREATE TABLE IF NOT EXISTS likes_img(
		img_id int NOT NULL,
		member_id int NOT NULL,
		PRIMARY KEY(img_id, member_id)
	)');
	echo "<p>Success!</p><br/><a href='../index.php'>Home</a>";
} catch (PDOException $e) {
	echo $e->getMessage();
	die();
}
?>
