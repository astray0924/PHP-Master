<?php
$options = array(
	'uri' => 'http://localhost',
	'location' => 'http://localhost/soap-server.php',
	'trace' => 1
);
$client = new SoapClient(NULL, $options);

echo $client->getDisplayName("Joe", "Bloggs");;
?>