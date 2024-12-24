<?php
require_once("data.inc.php");
require_once("util.inc.php");

const TIMEOUT_INTERVAL_SECONDS = 20 * 60;

const VERDICT_OFF = 0;
const VERDICT_POSSIBLE_OUTAGE = 1;
const VERDICT_ON = 2;

function get_modern_home_state() {
	// TODO: probably should clean up get_home_state
	$state = get_home_state();
	$reports = get_recent_checkins();

	// Verdict possibilities:
	// * 0 = off: have a negative report from the past 20 mins
	// * 1 = possible outage: no communication in the past 20 mins
	// * 2 = on: have a positive report from the past 20 mins
	// TODO: this sucks
	$verdict = VERDICT_POSSIBLE_OUTAGE;
	if ($state["status"]["text"] == "not powered") {
		$verdict = VERDICT_OFF;
	} elseif ($state["status"]["text"] == "online") {
		$verdict = VERDICT_ON;
	}

	$verdictTimestamp = $state["lastCheckin"]["timestamp"];

	return array(
		"verdict" => $verdict,
		"verdictTimestamp" => $verdictTimestamp,
		"reports" => $reports
	);
}

function get_home_state() {
	$lastCheckin = get_last_checkin();
	$lastCheckinDisplay = "unknown";
	$currentTimestamp = time();

	$isOnline = false;
	$isPowered = false;
	if ($lastCheckin != null) {
		if (abs($lastCheckin["timestamp"] - $currentTimestamp) < TIMEOUT_INTERVAL_SECONDS) {
			$isOnline = true;
			$isPowered = ($lastCheckin["powered"] == 1);
		}

		$lastCheckinDisplay = relative_time_html($lastCheckin["timestamp"]);
	}

	$status = ($isOnline ?
		($isPowered ? array(
			"icon" => "check2",
			"text" => "online",
			"color" => "green"
		) : array(
			"icon" => "exclamation",
			"text" => "not powered",
			"color" => "orange"
		)) : array(
			"icon" => "x",
			"text" => "completely offline",
			"color" => "red"
		)
	);

	return array(
		"status" => $status,
		"lastCheckin" => $lastCheckin,
		"lastCheckinDisplay" => $lastCheckinDisplay
	);
}