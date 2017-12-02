<?php
class Database {
	public static function getConnection() {
		global $config;

		$dbhost = $config['db']['host'];
		$dbuser = $config['db']['user'];
		$dbpass = $config['db']['pass'];
		$dbname = $config['db']['dbname'];
		$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		return $dbh;
    }
}
?>