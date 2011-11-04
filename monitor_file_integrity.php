<?php
define("PATH", ".");
define("DB_HOST", "localhost");
define("DB_NAME", "test");
define("DB_USER", "root");
define("DB_PASSWORD", "rudfhr88");

$files = array();

// extensions to fetch, an empty array will return all extensions
$ext = array("php");

// directories to ignore, an empty array will check all directories
$skip = array("logs", "logs/traffic");

// build profile
$dir = new RecursiveDirectoryIterator(PATH);
$iter = new RecursiveIteratorIterator($dir);
while ($iter -> valid()) {
	// skip unwanted directories
	if (!$iter -> isDot() && !in_array($iter -> getSubPath(), $skip)) {
		// get specific file extensions
		if (!empty($ext)) {
			// PHP 5.3.4: if (in_array($iter->getExtension(), $ext)) {
			if (in_array(pathinfo($iter -> key(), PATHINFO_EXTENSION), $ext)) {
				$files[$iter -> key()] = hash_file("sha1", $iter -> key());
			}
		} else {
			// ignore file extensions
			$files[$iter -> key()] = hash_file("sha1", $iter -> key());
		}
	}
	$iter -> next();
}

var_dump($files);

// DB에 삽입
$db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);

// Check discrepancies
// specific check for discrepancies
if (!empty($files)) {
	$result = $db -> query("SELECT * FROM integrity_hashes") -> fetchAll();
	if (!empty($result)) {
		$diffs = array();
		$tmp = array();
		foreach ($result as $value) {
			if (!array_key_exists($value["file_path"], $files)) {
				$diffs["del"][$value["file_path"]] = $value["file_hash"];
				$tmp[$value["file_path"]] = $value["file_hash"];
			} else {
				if ($files[$value["file_path"]] != $value["file_hash"]) {
					$diffs["alt"][$value["file_path"]] = $files[$value["file_path"]];
					$tmp[$value["file_path"]] = $files[$value["file_path"]];
				} else {
					// unchanged
					$tmp[$value["file_path"]] = $value["file_hash"];
				}
			}
		}
		if (count($tmp) < count($files)) {
			$diffs["add"] = array_diff_assoc($files, $tmp);
		}
		unset($tmp);
	}
}
 
// display discrepancies
if (!empty($diffs)) {
	echo "<p>The following discrepancies were found:</p>";
	echo "<ul>";
	foreach ($diffs as $status => $affected) {
		echo "<li>" . $status . "</li>";
		echo "<ol>";
		foreach ($affected as $path => $hash) {
			echo "<li>" . $path . "</li>";
		}
		echo "</ol>";
		echo "</ul>";
	}
} else {
	echo "<p>File structure is intact.</p>";
}

// clear old records
$db -> query("TRUNCATE integrity_hashes");

// insert updated records
$sql = "INSERT INTO integrity_hashes (file_path, file_hash) VALUES (:path, :hash)";
$sth = $db -> prepare($sql);
$sth -> bindParam(":path", $path);
$sth -> bindParam(":hash", $hash);
foreach ($files as $path => $hash) {
	$sth -> execute();
}

$db = null;
