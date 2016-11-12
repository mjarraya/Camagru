<?php
include 'config/database.php';
function connect(){
	global $DB_DSN, $DB_USER, $DB_PASSWORD;
try {
	$dbh = new PDO ($DB_DSN, $DB_USER, $DB_PASSWORD);
	return ($dbh);
} catch (PDOException $e) {
	echo $e->getMessage();
	die();
}
}
 ?>
