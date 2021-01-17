<?php
require_once("db.inc.php");

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