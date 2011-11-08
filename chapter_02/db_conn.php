<?php
try {
	$db_conn = new PDO('mysql:host=localhost;dbname=recipes', 'root', 'rudfhr88');
} catch (PDOException $e) {
	echo "Could not connect to database";
}
?>