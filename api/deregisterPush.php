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
if ($uuid == "") {
	write_response(array(
		"status" => "error",
		"error" => "missing_params"
	));
}

$existingDevice = get_push_device_by_uuid($uuid);
if ($existingDevice == null) {
	write_response(array(
		"status" => "error",
		"error" => "invalid_uuid"
	));
}

delete_push_device($existingDevice);

write_response(array(
	"status" => "ok"
));