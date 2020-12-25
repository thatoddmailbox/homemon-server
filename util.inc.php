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

// based off of https://stackoverflow.com/questions/9619823/php-how-do-i-calculate-relative-time
function relative_time_text($time) {
	if (!is_numeric($time)) {
		$time = strtotime($time);
	}

	$periods = array("second", "minute", "hour", "day", "week", "month", "year", "age");
	$lengths = array("60", "60", "24", "7", "4.35", "12", "100");

	$now = time();

	$difference = $now - $time;
	if ($difference <= 10 && $difference >= 0) {
		return "just now";
	}

	if ($difference > 0) {
		$tense = "ago";
	} else if ($difference < 0) {
		$tense = "later";
	}

	for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
		$difference = $difference / $lengths[$j];
	}

	$difference = round($difference);

	$period = $periods[$j] . ($difference > 1 ? "s" :"");
	return "{$difference} {$period} {$tense}";
}

function relative_time_html($time) {
	$relativeText = relative_time_text($time);
	$absoluteText = date("l, F j, Y, g:i:s a", $time);
	return '<time title="' . htmlspecialchars($absoluteText) . '">' . htmlspecialchars($relativeText) . '</time>';
}