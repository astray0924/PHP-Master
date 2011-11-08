<?php
use shop\shipping as s;

require 'autoload.php';

try {
	$db_conn = new PDO('mysql:host=localhost;dbname=recipes', 'root', 'rudfhr88');
} catch (PDOException $e) {
	echo "Could not connect to database";
}

// query for one recipes
$sql = 'SELECT recipes.name, recipes.description, categories.name 
			as category
		FROM recipes 
		INNER JOIN categories ON categories.id = recipes.category_id
		WHERE chef = :chef
		AND categories.name = :category_name';

$stmt = $db_conn -> prepare($sql);

// bind the chef value, we only want Lorna's recipes
$stmt -> bindValue(':chef', 'Lorna');
$stmt -> bindParam(':category_name', $category);

// starters
$category = 'Starter';
$stmt -> execute();
$starters = $stmt -> fetchAll();

// pudding
$category = 'Pudding';
$stmt -> execute();
$pudding = $stmt -> fetchAll();

// Getting last inserted ID
$sql = 'INSERT INTO recipes (name, description, chef, created)
		VALUES (:name, :description, :chef, NOW())';
		
$stmt = $db_conn->prepare($sql);

// perform query
$stmt->execute(array(
	':name' => 'Weekday Risotto',
	':description' => 'Creamy rice-based dish, boosted by in-season ingredients. Otherwise known as \'raid-the-fridge risotto\'',
	':chef' => 'Lorna')
	);
	
echo "New recipe ID: " . $db_conn->lastInsertId();
?>