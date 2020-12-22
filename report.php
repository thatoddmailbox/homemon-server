<?php
require_once("config.inc.php");
require_once("db.inc.php");
require_once("util.inc.php");

function write_response($response) {
	header("Content-Type: application/json");
	echo json_encode($response);
	die();
}

$providedToken = $_SERVER["HTTP_X_TOKEN"];
if ($providedToken == "" || !hash_equals(REPORT_TOKEN, $providedToken)) {
	write_response(array(
		"status" => "error",
		"error" => "invalid_token"
	));
}

$ip = get_ip_address();
$timestamp = time();

$stmt = $db->prepare("INSERT INTO reports(ip, `timestamp`) VALUES(?, ?)");
$stmt->execute(array(
	$ip,
	$timestamp
));

write_response(array(
	"status" => "ok"
));