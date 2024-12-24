<?php
header("Content-Type: application/json");
require_once("../apns.inc.php");
require_once("../data.inc.php");
require_once("../status.inc.php");

$state = get_modern_home_state();
$currentVerdict = $state["verdict"];

// for each device, see what they previously thought
// and then tell them if it's different now
$pushDevices = get_push_devices();

foreach ($pushDevices as $pushDevice) {
	if ($pushDevice["platform"] != PUSH_DEVICE_PLATFORM_APNS) {
		// don't know what to do with that
		continue;
	}

	$lastVerdict = $pushDevice["lastVerdict"];

	if ($lastVerdict == $currentVerdict) {
		// nothing new to tell them
		continue;
	}

	$showAlert = false;
	$alertTitle = "";
	$alertBody = "";

	if ($currentVerdict == VERDICT_OFF) {
		$showAlert = true;
		$alertTitle = "Power is out!";
		$alertBody = "There is no power at the " . HOME_NAME . ".";
	} else if ($currentVerdict == VERDICT_POSSIBLE_OUTAGE && $lastVerdict != VERDICT_OFF) {
		// note that we don't send an alert for the transition of VERDICT_OFF -> VERDICT_POSSIBLE_OUTAGE
		// this is because, if there is an extended outage, that would be expected (as the battery would drain)
		// and it does not make sense to send a "is out" followed by a "maybe out"
		$showAlert = true;
		$alertTitle = "Power may be out";
		$alertBody = "There could be a power outage at the " . HOME_NAME . ". Check other devices to make sure.";
	} else if ($currentVerdict == VERDICT_ON) {
		$showAlert = true;
		$alertTitle = "Power is back on";
		$alertBody = "Power has been restored to the " . HOME_NAME . ".";
	}

	update_push_device_last_verdict($pushDevice, $currentVerdict);

	if (!$showAlert) {
		continue;
	}

	send_apns_push($pushDevice["environment"], array(
		"aps" => array(
			"alert" => array(
				"title" => $alertTitle,
				"body" => $alertBody
			),
			"sound" => "default"
		)
	), generate_apns_token(), $pushDevice["deviceToken"], APNS_BUNDLE_ID);
}

echo json_encode(array(
	"status" => "ok"
));