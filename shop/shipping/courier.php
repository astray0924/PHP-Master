<?php
namespace shop\shipping;

use shop\products as p;

class Courier implements \Countable
{
	public $name;
	public $home_country;
	protected $count = 0;
	
	public function __construct($name, $home_country) {
		$this->name = $name;
		$this->home_country = $home_country;
		$this->logFile = $this->getLogFile();
		return true;
	}
	
	public function ship(p\Parcel $parcel) {
		$this->count++;
		// sends the parcel to its destination
		return true;
	}
	
	public function calculateShipping($parcel) {
		// look up the rate for the destination, we'll invent one
		$rate = 1.78;
		
		// calculate the cost
		$cost = $rate * $parcel->weight;
		return $cost;
	}
	
	public static function getCouriersByCountry($country) {
		// get a list of couriers with their home_country = $country
		$courier_list = array();
		
		// create a Courier object for each result
		
		// return an array of the results
		return $courier_list;
	}
	
	function saveAsPreferredSupplier(Courier $courier) {
		// add to list and save
		return true;
	}
	
	public function count() {
		return $this->count;
	}
	
	protected function getLogFile() {
		// error log location would be in a config file
		return fopen('./tmp/error_log.txt', 'a');
	}
	
	public function log($message) {
		if ($this->logFile) {
			fputs($this->logfile, 'Log message: ' . $message . "\n");
		}
	}
	
	public function __sleep() {
		// only store the "safe" properties
		return array("name", "home_country");
	}
	
	public function __wakeup() {
		// properties are restored, now add the log file
		$this->logfile = $this->getLogFile();
		return true;
	}
	
	public function __toString() {
		return $this->name . '(' . $this->home_country . ')';
	}
}
?>