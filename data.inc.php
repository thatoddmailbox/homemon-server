<?php
require_once("db.inc.php");

const PUSH_DEVICE_PLATFORM_APNS = 0;

function get_last_checkin() {
	global $db;
	$result = $db->query("SELECT * FROM reports ORDER BY `timestamp` DESC LIMIT 1")->fetchAll(PDO::FETCH_ASSOC);
	if (count($result) == 0) {
		return null;
	}
	return $result[0];
}

function get_recent_checkins() {
	global $db;
	$result = $db->query("SELECT * FROM reports ORDER BY `timestamp` DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
	return $result;
}

function get_push_devices() {
	global $db;
	$result = $db->query("SELECT * FROM push_devices ORDER BY `id` ASC")->fetchAll(PDO::FETCH_ASSOC);
	return $result;
}

function update_push_device_last_verdict($pushDevice, $currentVerdict) {
	global $db;
	$stmt = $db->prepare("UPDATE push_devices SET lastVerdict = ? WHERE id = ?");
	$stmt->execute(array($currentVerdict, $pushDevice["id"]));
}