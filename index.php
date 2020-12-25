<?php require_once("auth.inc.php"); ?>
<?php require_once("header.inc.php"); ?>
<?php
require_once("data.inc.php");
require_once("util.inc.php");

const TIMEOUT_INTERVAL_SECONDS = 20 * 60;

$lastCheckin = get_last_checkin();
$lastCheckinDisplay = "unknown";
$currentTimestamp = time();

$isOnline = false;
if ($lastCheckin != null) {
	if (abs($lastCheckin["clientTimestamp"] - $currentTimestamp) < TIMEOUT_INTERVAL_SECONDS) {
		$isOnline = true;
	}

	$lastCheckinDisplay = relative_time_html($lastCheckin["clientTimestamp"]);
}

$statusText = ($isOnline ? "online" : "offline");
$statusIcon = ($isOnline ? "check2" : "exclamation");
?>
<div class="container">
	<div class="mainInfo">
		<div class="mainInfoHeader display-6">
			<i class="bi bi-lg bi-<?php echo $statusIcon; ?>-circle"></i> Your home is <em><?php echo $statusText; ?></em>.
		</div>
		<div class="mainInfoSubheader lead">
			Last checkin: <?php echo $lastCheckinDisplay; ?>
		</div>
	</div>
</div>
<?php require_once("footer.inc.php"); ?>