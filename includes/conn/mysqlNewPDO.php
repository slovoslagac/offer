<?php
$servername = "192.168.180.124";
$username = "proske";
$password = "proske1989";

try {
	$conn = new PDO("mysql:host=$servername;dbname=Uporedna_new", $username, $password);
	// set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//echo "Connected successfully";
}
catch(PDOException $e)
{
	echo "Connection failed: " . $e->getMessage();
}
