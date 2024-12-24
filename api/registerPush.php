<?php
require_once("../data.inc.php");
require_once("../status.inc.php");

function write_response($response) {
	header("Content-Type: application/json");
	echo json_encode($response);
	die();
}

$authToken = $_POST["authToken"];
if ($authToken == "" || !hash_equals(PUSH_AUTH_TOKEN, $authToken)) {
	write_response(array(
		"status" => "error",
		"error" => "invalid_token"
	));
}

$uuid = $_POST["uuid"];

$platform = $_POST["platform"];
if ($platform != "0") {
	write_response(array(
		"status" => "error",
		"error" => "missing_params"
	));
}

$environment = $_POST["environment"];
if ($environment != "0" && $environment != "1") {
	write_response(array(
		"status" => "error",
		"error" => "invalid_params"
	));
}

$deviceToken = $_POST["deviceToken"];
if ($deviceToken == "") {
	write_response(array(
		"status" => "error",
		"error" => "missing_params"
	));
}

if (empty($uuid)) {
	// need to make a new device
	$currentVerdict = get_modern_home_state()["verdict"];
	$uuid = insert_push_device($platform, $environment, $deviceToken, $currentVerdict);
	write_response(array(
		"status" => "ok",
		"uuid" => $uuid
	));
} else {
	// updating existing device
	$existingDevice = get_push_device_by_uuid($uuid);
	if ($existingDevice == null) {
		write_response(array(
			"status" => "error",
			"error" => "invalid_uuid"
		));
	}

	update_push_device_device_token($existingDevice, $deviceToken);

	write_response(array(
		"status" => "ok",
		"uuid" => $uuid
	));
}