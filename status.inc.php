<?php
require_once("data.inc.php");
require_once("util.inc.php");

const TIMEOUT_INTERVAL_SECONDS = 20 * 60;

function get_home_state() {
	$lastCheckin = get_last_checkin();
	$lastCheckinDisplay = "unknown";
	$currentTimestamp = time();

	$isOnline = false;
	$isPowered = false;
	if ($lastCheckin != null) {
		if (abs($lastCheckin["clientTimestamp"] - $currentTimestamp) < TIMEOUT_INTERVAL_SECONDS) {
			$isOnline = true;
			$isPowered = ($lastCheckin["powered"] == 1);
		}

		$lastCheckinDisplay = relative_time_html($lastCheckin["clientTimestamp"]);
	}

	$status = ($isOnline ?
		($isPowered ? array(
			"icon" => "check2",
			"text" => "online"
		) : array(
			"icon" => "exclamation",
			"text" => "not powered"
		)) : array(
			"icon" => "x",
			"text" => "completely offline"
		)
	);

	return array(
		"status" => $status,
		"lastCheckin" => $lastCheckin,
		"lastCheckinDisplay" => $lastCheckinDisplay
	);
}