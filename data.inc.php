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

function get_push_device_by_uuid($uuid) {
	global $db;
	$stmt = $db->prepare("SELECT * FROM push_devices WHERE `uuid` = ?");

	$stmt->execute(array($uuid));
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($results) == 0) {
		return null;
	}

	return $results[0];
}

function update_push_device_last_verdict($pushDevice, $currentVerdict) {
	global $db;
	$stmt = $db->prepare("UPDATE push_devices SET lastVerdict = ? WHERE id = ?");
	$stmt->execute(array($currentVerdict, $pushDevice["id"]));
}

function update_push_device_device_token($pushDevice, $deviceToken) {
	global $db;
	$stmt = $db->prepare("UPDATE push_devices SET deviceToken = ? WHERE id = ?");
	$stmt->execute(array($deviceToken, $pushDevice["id"]));
}

function insert_push_device($platform, $environment, $deviceToken, $lastVerdict) {
	global $db;
	$uuid = bin2hex(openssl_random_pseudo_bytes(32));
	$stmt = $db->prepare("INSERT INTO push_devices(uuid, platform, environment, deviceToken, lastVerdict) VALUES(?, ?, ?, ?, ?)");
	$stmt->execute(array(
		$uuid, $platform, $environment, $deviceToken, $lastVerdict
	));
	return $uuid;
}