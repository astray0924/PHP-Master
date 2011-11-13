<?php
	$concerts = array(
		array('title' => 'The Magic Flute',
			'time' => 132944424),
		array('title' => 'Vivaldi Four Seasons',
			'time' => 123412444),
		array('title' => "Mozart's Requiem",
			"time" => 123124445)
		);
		
	echo json_encode($concerts);
?>