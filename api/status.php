<?php
header("Content-Type: application/json");

require_once("../data.inc.php");
require_once("../status.inc.php");

// TODO: probably should clean up get_home_state
$state = get_home_state();
$reports = get_recent_checkins();

// Verdict possibilities:
// * 0 = off: have a negative report from the past 20 mins
// * 1 = possible outage: no communication in the past 20 mins
// * 2 = on: have a positive report from the past 20 mins
// TODO: this sucks
$verdict = 0;
if ($state["status"]["text"] == "not powered") {
	$verdict = 1;
} elseif ($state["status"]["text"] == "online") {
	$verdict = 2;
}

$verdictTimestamp = $state["lastCheckin"]["timestamp"];

echo json_encode(array(
	"status" => "ok",
	"verdict" => $verdict,
	"verdictTimestamp" => $verdictTimestamp,
	"reports" => $reports
));