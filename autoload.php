<?php
class Autoload {
	static public function test($name) {
		include strtolower($name) . '.php';
	}

}

spl_autoload_register(__NAMESPACE__ . '\Autoload::test');
?>