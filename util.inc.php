<?php
require_once("db.inc.php");

function get_ip_address() {
	if (IP_FORWARDING_HEADER != "") {
		$normalizedName = "HTTP_" . strtoupper(str_replace("-", "_", IP_FORWARDING_HEADER));
		if (isset($_SERVER[$normalizedName])) {
			return $_SERVER[$normalizedName];
		}
	}

	return $_SERVER["REMOTE_ADDR"];
}

function get_last_checkin() {
	global $db;
	$result = $db->query("SELECT * FROM reports ORDER BY clientTimestamp DESC LIMIT 1")->fetchAll(PDO::FETCH_ASSOC);
	if (count($result) == 0) {
		return null;
	}
	return $result[0];
}