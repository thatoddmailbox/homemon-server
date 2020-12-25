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

$recentCheckins = get_recent_checkins();
?>
<div class="container">
	<div class="mainInfo">
		<div class="mainInfoHeader display-6">
			<i class="bi bi-lg bi-<?php echo $status["icon"]; ?>-circle"></i> Your home is <em><?php echo $status["text"]; ?></em>.
		</div>
		<div class="mainInfoSubheader lead">
			Last checkin: <?php echo $lastCheckinDisplay; ?>
		</div>
	</div>

	<div class="recentCheckins">
		<h4>Recent checkins</h4>
		<table class="table">
			<thead>
				<tr>
					<th scope="col">Time</th>
					<th scope="col">Powered?</th>
					<th scope="col">Battery level</th>
					<th scope="col">Battery voltage</th>
					<th scope="col">IP</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($recentCheckins as $checkin) { ?>
					<tr>
						<td><?php echo relative_time_html($checkin["clientTimestamp"]); ?></td>
						<td><?php echo ($checkin["powered"] == 1 ? "yes" : "no"); ?></td>
						<td><?php echo htmlspecialchars($checkin["batteryLevel"]) ?>%</td>
						<td><?php echo htmlspecialchars($checkin["batteryVoltage"] / 1000) ?> V</td>
						<td><?php echo htmlspecialchars($checkin["ip"]) ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<?php require_once("footer.inc.php"); ?>