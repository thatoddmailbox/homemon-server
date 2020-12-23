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

$poweredInput = $_GET["p"];
if ($poweredInput != "0" && $poweredInput != "1") {
	write_response(array(
		"status" => "error",
		"error" => "invalid_params"
	));
}
$powered = ($poweredInput == "1");

$batteryLevelInput = $_GET["b"];
if (!is_numeric($batteryLevelInput)) {
	write_response(array(
		"status" => "error",
		"error" => "invalid_params"
	));
}
$batteryLevel = intval($batteryLevelInput);

$batteryVoltageInput = $_GET["v"];
if (!is_numeric($batteryVoltageInput)) {
	write_response(array(
		"status" => "error",
		"error" => "invalid_params"
	));
}
$batteryVoltage = intval($batteryVoltageInput);

$ip = get_ip_address();
$timestamp = time();

$stmt = $db->prepare("INSERT INTO reports(powered, batteryLevel, batteryVoltage, ip, `timestamp`) VALUES(?, ?, ?, ?, ?)");
$stmt->execute(array(
	$powered ? "1" : "0",
	$batteryLevel,
	$batteryVoltage,
	$ip,
	$timestamp
));

write_response(array(
	"status" => "ok"
));