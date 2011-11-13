<?php
	$ch = curl_init('http://www.google.com');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	$result = curl_exec($ch);
	
	var_dump($result);
?>